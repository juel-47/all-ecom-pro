import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import axios from 'axios';
import debounce from 'lodash/debounce';
import { route } from 'ziggy-js';

const debouncedSync = debounce(() => {
    useCartStore.getState().syncCart();
}, 700);

// Cancel any pending sync on page unload to avoid race
if (typeof window !== 'undefined') {
    window.addEventListener('beforeunload', () => {
        debouncedSync.cancel();
    });
}

export const useCartStore = create(
    persist(
        (set, get) => ({
            cartItems: [],
            total: 0,
            cartCount: 0,

            // Recalculate: ensures quantity is number, and cartCount is unique items count
            recalculate: (items) => {
                const total = items.reduce((sum, i) => {
                    const quantity = Number(i.quantity) || 1; 
                    
                    // Multi-layer price fallback
                    let rawPrice = i.price;
                    let basePrice = parseFloat(rawPrice);
                    
                    // If item price is missing, NaN, or 0, check the attached product info
                    if (isNaN(basePrice) || basePrice <= 0) {
                        const prod = i.product || {};
                        const effectivePrice = parseFloat(prod.effective_price);
                        const regularPrice = parseFloat(prod.price);
                        basePrice = (!isNaN(effectivePrice) && effectivePrice > 0) ? effectivePrice : (!isNaN(regularPrice) ? regularPrice : 0);
                    }
                    
                    if (isNaN(basePrice)) basePrice = 0;
                    
                    const variantTotal = parseFloat(i.options?.variant_total || 0) || 0;

                    const itemTotalPrice = basePrice + variantTotal;
                    
                    return sum + (itemTotalPrice * quantity);
                }, 0);

                const count = items.length; 
                
                const result = {
                    cartItems: items,
                    total: Number(total.toFixed(2)),
                    cartCount: count,
                };
                
                return result;
            },

            setCart: (items) => {
                // Normalize quantities to numbers when setting from server
                const normalizedItems = Array.isArray(items) 
                    ? items.map(item => ({
                        ...item,
                        quantity: Number(item.quantity) || 1,
                    }))
                    : [];
                const state = get().recalculate(normalizedItems);
                set(state);
            },

            increment: (cartId, availableStock) => {
                set((state) => {
                    const items = state.cartItems.map((item) => {
                        if (item.id === cartId) {
                            const currentQty = Number(item.quantity) || 1;
                            const newQty = currentQty + 1;
                            if (newQty > availableStock) return item;
                            return { ...item, quantity: newQty };
                        }
                        return item;
                    });

                    return get().recalculate(items);
                });

                debouncedSync(); 
            },

            decrement: (cartId) => {
                set((state) => {
                    const items = state.cartItems
                        .map((item) => {
                            if (item.id === cartId) {
                                const currentQty = Number(item.quantity) || 1;
                                if (currentQty <= 1) return null; // will be filtered
                                return { ...item, quantity: currentQty - 1 };
                            }
                            return item;
                        })
                        .filter(Boolean); // remove nulls

                    return get().recalculate(items);
                });

                debouncedSync();
            },

            remove: (cartId) => {
                set((state) => {
                    const items = state.cartItems.filter((item) => item.id !== cartId);
                    return get().recalculate(items);
                });

                // Fire and forget delete
                axios.delete(route('cart.remove', cartId)).catch(console.error);
            },

            clearCart: () => {
                set({
                    cartItems: [],
                    total: 0,
                    cartCount: 0,
                });

                axios.delete(route('cart.clear')).catch(console.error);
            },

            addItem: (product, quantity = 1, options = {}) => {
                set((state) => {
                    const existingItemIndex = state.cartItems.findIndex(
                        (item) => 
                            item.product_id === product.id && 
                            item.options?.color_id === options.color_id && 
                            item.options?.size_id === options.size_id
                    );

                    let newItems;
                    if (existingItemIndex > -1) {
                        newItems = [...state.cartItems];
                        newItems[existingItemIndex] = {
                            ...newItems[existingItemIndex],
                            quantity: Number(newItems[existingItemIndex].quantity) + Number(quantity)
                        };
                    } else {
                        newItems = [
                            ...state.cartItems,
                            {
                                id: Date.now(), // Temporary ID for optimistic UI
                                product_id: product.id,
                                name: product.name,
                                // Use offer_price if available, otherwise regular price
                                price: (product.effective_price && parseFloat(product.effective_price) > 0) ? product.effective_price : product.price,
                                product: product, // Store product for better recalculation fallbacks
                                quantity: Number(quantity),
                                options: {
                                    ...options,
                                    variant_total: parseFloat(options.variant_total || 0),
                                }
                            }
                        ];
                    }
                    return get().recalculate(newItems);
                });
                // Note: We don't call debouncedSync() here because the actual persistence 
                // is handled by the server-side route (e.g. router.post('/cart/add')).
                // This method is primarily for instant UI feedback (optimistic update).
            },

            syncCart: async () => {
                const items = get().cartItems;

                // Prevent sending empty or invalid data
                if (!items || items.length === 0) return;

                try {
                    await axios.post(route('cart.sync'), {
                        items: items.map((i) => ({
                            id: i.id,
                            quantity: Number(i.quantity), // Always send as number
                        })),
                    });
                } catch (e) {
                    console.error('Cart sync failed', e);
                }
            },
        }),
        {
            name: 'cart-storage',
            partialize: (state) => ({
                cartItems: state.cartItems.map(item => ({
                    ...item,
                    quantity: Number(item.quantity), // Ensure saved as number
                })),
                total: state.total,
                cartCount: state.cartCount,
            }),
            // Customize how state is restored
            onRehydrateStorage: () => (state) => {
                if (state) {
                    // Normalize quantities when rehydrating from localStorage
                    state.cartItems = Array.isArray(state.cartItems) 
                        ? state.cartItems.map(item => ({
                            ...item,
                            quantity: Number(item.quantity) || 1,
                        }))
                        : [];
                    // Recalculate after rehydration
                    const storeState = useCartStore.getState();
                    const recalculated = storeState.recalculate(state.cartItems);
                    useCartStore.setState(recalculated);
                }
            },
        }
    )
);

export default useCartStore;
