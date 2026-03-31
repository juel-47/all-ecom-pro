// CartPage.jsx
import React, { useEffect, useState } from 'react';
import { Trash2, Plus, Minus, ArrowLeft, ShoppingBag, CreditCard, Truck, CheckCircle } from 'lucide-react';
import { usePage, useForm, Link } from '@inertiajs/react';
import { useCartStore } from "../stores/cartStore";
import toast from "react-hot-toast";

const CartPage = () => {
  const { cart_items, total: serverTotal, settings, promotions = [], shipping_methods = [], pickup_methods = [], countries = [], customer_addresses = [] } = usePage().props;
  const cartItems = useCartStore(state => state.cartItems);
  const cartTotal = useCartStore(state => state.total);
  const { setCart, increment, decrement, remove, clearCart } = useCartStore();


  // Initialize cart store with server data
  useEffect(() => {
    setCart(cart_items ?? []);
    // If cart has items and user wants to checkout immediately, we could toggle this.
    // For now, let's keep it as a separate step or just show it below.
  }, [cart_items]);

  // Checkout Form Logic
  const { data, setData, post, processing, errors, transform } = useForm({
    // UI Fields
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    street_address: '',
    city: '',
    zip_code: '',
    country: 'Denmark',
    
    // Selection
    shipping_method_id: '',
    payment_method: 'paypal', 
    
    // Internal storage for full objects required by backend
    shipping_method_obj: null,
  });

  const [selectedShippingCost, setSelectedShippingCost] = useState(0);

 

  // Pre-fill address
  useEffect(() => {
      if (customer_addresses && customer_addresses.length > 0) {
          const defaultAddress = customer_addresses.find(a => a.is_default) || customer_addresses[0];
          setData(prev => ({
              ...prev,
              first_name: defaultAddress.first_name || '',
              last_name: defaultAddress.last_name || '',
              email: defaultAddress.email || '',
              phone: defaultAddress.phone || '',
              street_address: defaultAddress.address || '',
              city: defaultAddress.city || '',
              zip_code: defaultAddress.zip_code || '',
              country: defaultAddress.country || 'Denmark',
          }));
      }
  }, [customer_addresses]);

  // Transform data before submit
  transform((data) => ({
      payment_method: data.payment_method,
      shipping_method: data.shipping_method_obj,
      shipping_address: {
          address: data.street_address,
          city: data.city,
          zip_code: data.zip_code,
          country: data.country
      },
      personal_info: {
          first_name: data.first_name,
          last_name: data.last_name,
          email: data.email,
          phone: data.phone
      }
  }));

  const handleShippingChange = (method) => {
      setSelectedShippingCost(Number(method.cost));
      setData(data => ({
          ...data,
          shipping_method_id: method.id,
          shipping_method_obj: method
      }));
  };

  const handleCheckoutSubmit = (e) => {
      e.preventDefault();
      post(route('checkout.store'));
  };


  // Cart Actions
  const handlePlus = (id, currentQty, availableStock) => {
    if (currentQty >= availableStock) {
      toast.warn(`Only ${availableStock} item(s) available`);
      return;
    }
    increment(id, availableStock);
  };

  const handleMinus = (id, currentQty) => {
    if (currentQty <= 1) return;
    decrement(id);
  };

  const handleRemove = (id) => {
    remove(id);
    toast.success("Product removed from cart", { id: 'cart-page-toast' });
  };

  const handleClearCart = () => {
      clearCart();
      toast.success("Cart cleared", { id: 'cart-page-toast' });
  };

  // Use store total if available, otherwise fallback to server provided total
  const subtotal = Number(cartTotal > 0 ? cartTotal : serverTotal) || 0;
  
  // Shipping logic is now handled by selectedShippingCost in the form
  // Only show generic shipping estimate if NOT in checkout mode?
  // Actually, let's use selectedShippingCost for the total calculation if checkout is active.
  
  const finalTotal = Number(subtotal) + (Number(selectedShippingCost) || 0);
  const totalItems = cartItems.length;


  return (
    <div className="min-h-screen bg-gray py-8 px-4 sm:px-6 lg:px-8">
      <div className="container">
        
        {/* Header */}
        <div className="flex items-center justify-between mb-8">
          <h1 className="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <ShoppingBag size={32} className="text-red" />
            Your Cart
          </h1>
          <div className="flex items-center gap-6">
            <span className="text-lg text-gray-600 hidden sm:block">
                {cartItems.length} {cartItems.length === 1 ? 'item' : 'items'}
            </span>
            {cartItems.length > 0 && (
                <button 
                    onClick={handleClearCart}
                    className="text-red hover:text-red-700 font-medium flex items-center gap-2 transition-colors border-b border-transparent hover:border-red"
                >
                    <Trash2 size={18} />
                    <span>Clear All</span>
                </button>
            )}
          </div>
        </div>

        {cartItems.length === 0 ? (
          <EmptyCart />
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {/* Left Column: Cart Items + (Optional) Checkout Form */}
            <div className="lg:col-span-8 space-y-8">
              {/* Cart Items List */}
              <div className="bg-white rounded-xl shadow-sm border border-gray overflow-hidden">
                {cartItems.map((item) => (
                  <div
                    key={item.id}
                    className="p-5 sm:p-6 flex flex-col sm:flex-row gap-5 border-b border-gray last:border-b-0"
                  >
                    {/* Image */}
                    <div className="w-full sm:w-32 h-32 shrink-0">
                      <img
                        src={`/${item.product?.thumb_image || item.options?.image || item.image}`}
                        alt={item.product?.name || item.name}
                        className="w-full h-full object-cover rounded-lg"
                      />
                    </div>

                    {/* Details */}
                    <div className="flex-1">
                      <div className="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                        <div>
                          <h3 className="font-medium text-gray-900 text-lg">
                            {item.product?.name || item.name}
                          </h3>
                          <p className="text-sm text-gray-500 mt-1">
                            {item.options?.color_name && <span>Color: {item.options.color_name}</span>}
                            {item.options?.color_name && item.options?.size_name && <span>, </span>}
                            {item.options?.size_name && <span>Size: {item.options.size_name}</span>}
                            {!(item.options?.color_name || item.options?.size_name) && item.variant}
                          </p>
                          {parseFloat(item.options?.variant_total || 0) > 0 && (
                            <p className="text-sm font-bold text-gray-700 mt-1">
                               variant extra : + {settings?.currency_icon || 'DKK.'}{parseFloat(item.options.variant_total).toFixed(2)}
                            </p>
                          )}
                          <p className="text-lg font-semibold text-red mt-2">
                            {settings?.currency_icon || '€'}
                            {(parseFloat(item.price && item.price > 0 ? item.price : (item.product?.effective_price || item.product?.price || 0))).toFixed(2)}
                          </p>
                        </div>

                        {/* Mobile remove */}
                        <button
                          onClick={() => handleRemove(item.id)}
                          className="sm:hidden text-red-600 hover:text-red-800 self-start"
                        >
                          <Trash2 size={20} />
                        </button>
                      </div>

                      {/* Quantity & Remove (desktop) */}
                      <div className="mt-4 flex items-center justify-between sm:justify-start gap-6">
                        <div className="flex items-center border border-gray rounded-md overflow-hidden">
                          <button
                            onClick={() => handleMinus(item.id, item.quantity)}
                            className="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition"
                          >
                            <Minus size={16} />
                          </button>
                          <span className="px-4 py-2 font-medium min-w-12 text-center">
                            {item.quantity}
                          </span>
                          <button
                            onClick={() => handlePlus(item.id, item.quantity, item.product?.qty || 100)}
                            className="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition"
                          >
                            <Plus size={16} />
                          </button>
                        </div>

                        <button
                          onClick={() => handleRemove(item.id)}
                          className="hidden sm:flex items-center gap-1.5 text-gray-500 hover:text-red transition"
                        >
                          <Trash2 size={18} />
                          <span className="text-sm">Remove</span>
                        </button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>



            </div>

            {/* Order Summary Right Column */}
            <div className="lg:col-span-4">
              <div className="bg-white rounded-xl shadow-sm border border-gray p-6 sticky top-8">
                <h2 className="text-xl font-semibold text-gray-900 mb-6">
                  Order Summary
                </h2>

                <div className="space-y-4">
                  <div className="flex justify-between text-gray-600">
                    <span>Subtotal ({totalItems} items)</span>
                    <span>{settings?.currency_icon || 'DKK.'}{subtotal.toFixed(2)}</span>
                  </div>
                  
                  <div className="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span>Calculated at checkout</span>
                  </div>
                  <div className="border-t border-gray pt-4 mt-2">
                    <div className="flex justify-between text-lg font-bold text-gray-900">
                      <span>Total</span>
                      <span>{settings?.currency_icon || 'DKK.'}{finalTotal.toFixed(2)}</span>
                    </div>
                    <p className="text-sm text-gray-500 mt-1">
                      Including VAT
                    </p>
                  </div>
                </div>

                <div className="mt-6">
                    <Link
                      href={route('checkout')}
                      className="block w-full text-center bg-red text-white py-4 rounded-lg font-medium hover:bg-red-800 transition duration-200 shadow-md"
                    >
                      Proceed to Checkout
                    </Link>
                </div>

                <div className="mt-6 text-center text-sm text-gray-500">
                  Secure checkout • Free returns within 30 days
                </div>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

const EmptyCart = () => (
  <div className="bg-white rounded-xl shadow-sm border border-gray p-12 text-center">
    <ShoppingBag size={64} className="mx-auto text-gray mb-6" />
    <h2 className="text-2xl font-semibold text-gray-800 mb-3">Your cart is empty</h2>
    <p className="text-gray-600 mb-8 max-w-md mx-auto">
      Looks like you haven't added anything yet. Let's change that!
    </p>
    <Link
      href={route('all.products')}
      className="inline-block bg-red text-white px-8 py-4 rounded-lg font-medium hover:bg-red-800 transition"
    >
      Start Shopping
    </Link>
  </div>
);

export default CartPage;