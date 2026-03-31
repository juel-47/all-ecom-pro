import React, { useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import toast from 'react-hot-toast';
import axios from 'axios';
import { route } from 'ziggy-js';
import useCartStore from '../stores/cartStore';
import Skeleton from './Skeleton';

const ProductCard = ({ product, onImageLoad, forceLoading = false }) => {
    const { settings } = usePage().props;
    const { addItem } = useCartStore();
    const currencyIcon = settings?.currency_icon;
    
    const [imageError, setImageError] = useState(false);
    const [isInternalLoading, setIsInternalLoading] = useState(true);
    const [isAdding, setIsAdding] = useState(false);
    
    const isImageLoading = isInternalLoading || forceLoading;
    
    const [quantity, setQuantity] = useState(1);
    const [isHovered, setIsHovered] = useState(false);

    const isOutOfStock = !product.qty || product.qty <= 0;
    const hasOptions = (Number(product?.colors_count) > 0) || 
                       (Number(product?.sizes_count) > 0) || 
                       (Number(product?.product_image_galleries_count) > 0) ||
                       (product?.colors?.length > 0) || 
                       (product?.sizes?.length > 0);

    const handleAddToCart = async () => {
        if (isAdding || isOutOfStock) return;
        
        // 1. INSTANT UI FEEDBACK (Optimistic)
        addItem(product, 1);
        toast.success("Product added to cart!", { id: 'cart-toast' });

        setIsAdding(true);

        try {
            // 2. FAST BACKGROUND SYNC
            await axios.post(route("cart.add"), {
                product_id: product.id,
                qty: 1,
                size_id: null,
                color_id: null,
            });
            // Server success: already handled UI optimistically
        } catch (error) {
            console.error("Cart sync error:", error);
            const errorMsg = error.response?.data?.message || "Failed to sync cart";
            toast.error(errorMsg, { id: 'cart-toast' });
        } finally {
            setIsAdding(false);
        }
    };

  const hasVariants = hasOptions; // Mapping for compatibility with existing JSX if needed

  return (
    <div
      className={`
                bg-white rounded-xl overflow-hidden border border-black/30 shadow-lg hover:shadow-md 
                transition-all duration-300    
              `} >
      {/* Product Image */}
      <div className="aspect-square relative bg-gray-100 overflow-hidden flex items-center justify-center">
        <Link href={`/product-details/${product.slug}`} prefetch className="w-full h-full flex items-center justify-center">
          {product.thumb_image && !imageError ? (
            <>
              {isImageLoading && (
                <Skeleton className="absolute inset-0 z-10" />
              )}
              <img
                src={product.thumb_image.startsWith('http') ? product.thumb_image : (product.thumb_image.startsWith('storage/') ? `/${product.thumb_image}` : `/storage/${product.thumb_image}`)}
                alt={product.name || "Product Image"}
                onLoad={() => {
                   setIsInternalLoading(false);
                   if (onImageLoad) onImageLoad();
                }}
                onError={() => {
                   setImageError(true);
                   setIsInternalLoading(false);
                   if (onImageLoad) onImageLoad();
                }}
                className={`max-h-full max-w-full object-contain group-hover:scale-105 transition-all duration-500 ${isImageLoading ? 'opacity-0' : 'opacity-100'}`}
                loading="lazy"
              />
            </>
          ) : (
            <div className="w-full h-full bg-gray-200 flex items-center justify-center">
              <div className="flex flex-col items-center gap-2 opacity-40">
                <svg className="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span className="text-[10px] font-semibold uppercase tracking-wider text-gray-500">No Image</span>
              </div>
            </div>
          )}
        </Link>
      </div>

      {/* Content */}
      <div className="p-3 sm:p-4 text-start">
        <Link href={`/product-details/${product.slug}`} prefetch>
          <h3 className="text-xs sm:text-sm font-medium text-gray-700 min-h-10 line-clamp-2">
            {product.name}
          </h3>
        </Link>


        <div className="mt-2 flex flex-col">
            {product.effective_price < product.price ? (
                <>
                    {product.active_campaign_product && (
                        <div className="text-[10px] font-bold text-red uppercase mb-0.5">
                            {product.active_campaign_product.discount_type === 'percentage' 
                                ? `SAVE ${Math.round(product.active_campaign_product.discount_value)}%` 
                                : `SAVE ${product.active_campaign_product.discount_value} ${currencyIcon || 'DKK.'}`}
                        </div>
                    )}
                    <div className="flex items-center gap-2">
                        <span className="text-base font-bold text-gray-900">
                            {currencyIcon || 'DKK.'}{product.effective_price}
                        </span>
                        <span className="text-xs text-gray-400 line-through">
                            {currencyIcon || 'DKK.'}{product.price}
                        </span>
                    </div>
                </>
            ) : (
                <span className="text-base font-bold text-gray-900">
                    {currencyIcon || 'DKK.'}{product.price}
                </span>
            )}
        </div>

        <p className="mt-1 text-xs font-medium flex items-center gap-1.5">
          <span className={product.qty > 0 ? "text-green-600" : "text-red-500"}>●</span>
          {product.qty > 0 ? `In Stock ${product.qty}` : "Out of Stock"}
        </p>

        <div className="mt-3 flex flex-col gap-2">
          {hasVariants ? (
            <Link
              href={`/product-details/${product.slug}`}
              prefetch
              className="
                        w-full bg-gray-800 text-white 
                        py-2 px-4 rounded-lg text-sm font-medium
                        hover:bg-gray-900 active:scale-98 transition-all duration-200
                        flex items-center justify-center gap-2 cursor-pointer
                      "
            >
              Select Options
            </Link>
          ) : (
            <button
              onClick={handleAddToCart}
              disabled={isAdding || isOutOfStock}
              className="
                        w-full bg-red text-white 
                        py-2 px-4 rounded-lg text-sm font-medium
                        hover:bg-red-800 active:scale-98 transition-all duration-200
                        flex items-center justify-center gap-2 cursor-pointer
                        disabled:opacity-50 disabled:cursor-not-allowed
                      "
            >
              <svg
                className="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              Add to Cart
            </button>
          )}

          <Link
            href={`/product-details/${product.slug}`}
            prefetch
            className="
                      w-full bg-white text-gray-800 border border-gray-400
                      py-2 px-4 rounded-lg text-sm font-medium
                      hover:bg-gray-50 active:scale-98 transition-all duration-200
                      flex items-center justify-center gap-2 cursor-pointer
                    "
          >
            Details
          </Link>
        </div>
      </div>
    </div>
  );
};

export default ProductCard;