 // ProductDetailsFull.jsx - Complete version with Customer Review Form
import React, { useState } from 'react';
import { router, usePage, Link } from '@inertiajs/react';
import toast from 'react-hot-toast';
import axios from 'axios';
import { route } from 'ziggy-js';
import useCartStore from '../stores/cartStore';
import Breadcrumb from '../components/Breadcrumb';
import ProductCard from '../components/ProductCard'; 
import ImageZoom from "react-image-zooom";
import Skeleton from '../components/Skeleton';

const ProductDetailsFull = ({ product, reviews = [], relatedProducts = [] }) => {
  const [imageError, setImageError] = useState({});
  const [isLargeImageLoading, setIsLargeImageLoading] = useState(true);
  // console.log(relatedProducts);
  // Data Normalization
  const normalizedProduct = {
    ...product,
    description: product.long_description || product.description,
    shortDesc: product.short_description || product.shortDesc,
    price: product.effective_price || product.price || 0,
    oldPrice: product.effective_price < product.price ? product.price : null,
    rating: Number(product.reviews_avg_rating) || 0,
    reviewCount: Number(product.reviews_count) || reviews.length || 0,
    colors: product.colors || [],
    sizes: product.sizes || [],
    stock: product.qty || 0,
  };

  // Enhanced Color derivation: use product.colors OR unique colors from gallery
  const derivedColors = React.useMemo(() => {
    if (normalizedProduct.colors && normalizedProduct.colors.length > 0) return normalizedProduct.colors;
    
    // Fallback: collect colors from gallery images if they have color data
    const galleryColors = (product.product_image_galleries || [])
      .filter(item => item.color)
      .map(item => item.color);
    
    // Get unique colors by ID
    const unique = [];
    const ids = new Set();
    galleryColors.forEach(c => {
      if (!ids.has(c.id)) {
        ids.add(c.id);
        unique.push(c);
      }
    });
    return unique;
  }, [normalizedProduct.colors, product.product_image_galleries]);

  const [selectedImage, setSelectedImage] = useState(0);
  const [selectedColor, setSelectedColor] = useState('');
  const [selectedSize, setSelectedSize] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [activeTab, setActiveTab] = useState('description');
  const [isAdding, setIsAdding] = useState(false);
  const [isBuying, setIsBuying] = useState(false);

  // Reset states when product changes
  React.useEffect(() => {
    setSelectedImage(0);
    setImageError({});
    setIsLargeImageLoading(true);
  }, [product.id]);

  // Sync selection once product is loaded
  React.useEffect(() => {
    if (derivedColors.length > 0 && !selectedColor) {
      setSelectedColor(derivedColors[0].color_name);
    }
  }, [derivedColors]);

  React.useEffect(() => {
    if (normalizedProduct.sizes.length > 0 && !selectedSize) {
      setSelectedSize(normalizedProduct.sizes[0].size_name);
    }
  }, [normalizedProduct.sizes]);

  // Review Form States
  const [reviewRating, setReviewRating] = useState(0);
  const [reviewText, setReviewText] = useState('');
  const [reviewSubmitted, setReviewSubmitted] = useState(false);
  const [isSubmittingReview, setIsSubmittingReview] = useState(false);

  const { auth, settings } = usePage().props;

  // Image path helper
  const formatImagePath = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/') || path.startsWith('/uploads/')) return path;
    if (path.startsWith('storage/') || path.startsWith('uploads/')) return `/${path}`;
    const cleanPath = path.startsWith('/') ? path.substring(1) : path;
    return `/storage/${cleanPath}`;
  };

  // Process all gallery images for the thumbnails
  const currentImages = React.useMemo(() => {
    const galleryImages = (product.product_image_galleries || []).map(item => formatImagePath(item.image));
    if (galleryImages.length > 0) return galleryImages;
    return [formatImagePath(product.thumb_image)].filter(Boolean);
  }, [product.product_image_galleries, product.thumb_image]);

  const NoImageSkeleton = ({ className = "" }) => (
    <div className={`w-full h-full bg-gray-100 flex flex-col items-center justify-center gap-3 p-6 ${className}`}>
        <svg className="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span className="text-sm font-medium text-gray-400 uppercase tracking-widest">No Image available</span>
    </div>
  );

  const handleReviewSubmit = (e) => {
    e.preventDefault();
    if (!auth?.user) {
      router.visit('/customer/login');
      return;
    }

    if (reviewRating === 0 || !reviewText.trim()) {
      toast.error("Please select a rating and write your review");
      return;
    }

    setIsSubmittingReview(true);
    
    router.post('/review', {
      product_id: product.id,
      rating: reviewRating,
      review: reviewText,
    }, {
      onSuccess: () => {
        setIsSubmittingReview(false);
        setReviewSubmitted(true);
        setReviewRating(0);
        setReviewText('');
        toast.success("Review submitted! It will be visible after approval.");
      },
      onError: () => {
        setIsSubmittingReview(false);
      },
      preserveScroll: true
    });
  };

  const handleAddToCart = async (isBuyNow = false) => {
    if (isAdding || isBuying) return;

    if (derivedColors.length > 0 && !selectedColor) {
      toast.error("Please select a color");
      return;
    }

    if (normalizedProduct.sizes.length > 0 && !selectedSize) {
      toast.error("Please select a size");
      return;
    }
    
    const selectedColorObj = derivedColors.find(c => c.color_name === selectedColor);
    const selectedSizeObj = normalizedProduct.sizes.find(s => s.size_name === selectedSize);

    const data = {
      product_id: product.id,
      qty: quantity,
      color_id: selectedColorObj?.id || null,
      size_id: selectedSizeObj?.id || null,
    };

    const variantTotal = (parseFloat(selectedColorObj?.pivot?.color_price || 0) + 
                          parseFloat(selectedSizeObj?.pivot?.size_price || 0));

    if (isBuyNow) {
        setIsBuying(true);
        // Instant store update before navigation
        const { addItem } = useCartStore.getState();
        addItem(product, quantity, {
            color_id: data.color_id,
            size_id: data.size_id,
            color_name: selectedColor,
            size_name: selectedSize,
            variant_total: variantTotal
        });

        router.post(route('cart.add'), data, {
            onSuccess: () => {
                router.visit(route('checkout'));
            },
            onError: (errors) => {
                setIsBuying(false);
                const errorMsg = Object.values(errors)[0] || 'Something went wrong';
                toast.error(errorMsg);
            },
            onFinish: () => {
                // We don't necessarily need to set it to false here if we are redirecting,
                // but for robustness if navigation fails:
                // setIsBuying(false);
            },
            preserveScroll: true
        });
        return;
    }

    // 1. INSTANT UI FEEDBACK (Optimistic)
    const { addItem } = useCartStore.getState();
    addItem(product, quantity, {
        color_id: data.color_id,
        size_id: data.size_id,
        color_name: selectedColor,
        size_name: selectedSize,
        variant_total: variantTotal
    });
    toast.success("Product added to cart!", { id: 'cart-toast' });

    // 2. BACKGROUND SYNC (Non-blocking)
    try {
        await axios.post(route('cart.add'), data);
    } catch (error) {
        console.error("Cart sync error:", error);
        const errorMsg = error.response?.data?.message || "Failed to sync cart";
        toast.error(errorMsg, { id: 'cart-toast' });
    }
  };

  const handleBuyNow = () => {
    handleAddToCart(true);
  };

  const currentImageUrl = currentImages[selectedImage];

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container px-4 sm:px-6 lg:px-8 py-6 md:py-10">
        <Breadcrumb
          customItems={[
            { name: normalizedProduct.name },
          ]}
        />

        {/* ==================== MAIN PRODUCT SECTION ==================== */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-12">
          {/* Gallery */}
          <div className="space-y-4">
            <div className="bg-white rounded-xl overflow-hidden border border-gray shadow-sm aspect-square relative">
              {currentImageUrl && !imageError[selectedImage] ? (
                <>
                  {/* Use a hidden img tag to reliably detect load/error states */}
                  <img 
                    src={currentImageUrl}
                    alt=""
                    className="hidden"
                    onLoad={() => setIsLargeImageLoading(false)}
                    onError={() => {
                        setIsLargeImageLoading(false);
                        setImageError(prev => ({ ...prev, [selectedImage]: true }));
                    }}
                  />
                  {isLargeImageLoading && (
                    <Skeleton className="absolute inset-0 z-10" />
                  )}
                  <ImageZoom
                    src={currentImageUrl}  
                    alt={`${normalizedProduct.name} - ${selectedColor}`}
                    zoom="200"   
                    className={`w-full h-full object-contain ${isLargeImageLoading ? 'opacity-0' : 'opacity-100'}`}
                  />
                </>
              ) : (
                <NoImageSkeleton />
              )}
            </div>

            <div className="grid grid-cols-4 gap-3">
              {currentImages.map((img, idx) => (
                <button
                  key={idx}
                  onClick={() => {
                    if (selectedImage !== idx) {
                        setIsLargeImageLoading(true);
                        setSelectedImage(idx);
                    }
                    // Sync color if this image has a color associated
                    const galleryItem = product.product_image_galleries?.[idx];
                    if (galleryItem && galleryItem.color_id) {
                      const colorObj = derivedColors.find(c => parseInt(c.id) === parseInt(galleryItem.color_id));
                      if (colorObj) setSelectedColor(colorObj.color_name);
                    }
                  }}
                  className={`
                    aspect-square rounded-lg overflow-hidden border-2 transition-all duration-200
                    ${
                      selectedImage === idx
                        ? "border-red shadow-md scale-105"
                        : "border-gray hover:border-red-300"
                    }
                  `}
                >
                  {img && !imageError[idx] ? (
                    <img
                      src={img}
                      alt=""
                      onError={() => setImageError(prev => ({ ...prev, [idx]: true }))}
                      className="w-full h-full object-cover"
                    />
                  ) : (
                    <div className="w-full h-full bg-gray-100 flex items-center justify-center">
                      <svg className="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  )}
                </button>
              ))}
            </div>
          </div>

          {/* Product Info */}
          <div className="space-y-6 lg:sticky lg:top-6 self-start">
            <div>
              <h1 className="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900">
                {normalizedProduct.name}
              </h1>

              <div className="mt-3 flex flex-wrap items-center gap-4">
                {product.active_campaign_product && (
                    <div className="w-full mb-1">
                        <span className="bg-red text-white text-xs font-bold px-3 py-1 rounded shadow-sm uppercase">
                            {product.active_campaign_product.discount_type === 'percentage' 
                                ? `${Math.round(product.active_campaign_product.discount_value)}% OFF` 
                                : `SAVE ${product.active_campaign_product.discount_value} ${settings?.currency_icon || 'DKK.'}`}
                            {" "} - CAMPAIGN PRICE
                        </span>
                    </div>
                )}
                <div className="flex items-center gap-3">
                  <span className="text-3xl md:text-4xl font-bold text-red">
                    {settings?.currency_icon || 'DKK.'}{normalizedProduct.price}
                  </span>
                  {normalizedProduct.oldPrice && normalizedProduct.oldPrice > 0 && (
                    <span className="text-xl text-gray-500 line-through">
                      {settings?.currency_icon || 'DKK.'}{normalizedProduct.oldPrice}
                    </span>
                  )}
                </div>

                <div className="flex items-center gap-2">
                  <div className="flex">
                    {[...Array(5)].map((_, i) => (
                      <svg
                        key={i}
                        className={`w-5 h-5 ${
                          i < Math.floor(normalizedProduct.rating)
                            ? "text-yellow-400"
                            : "text-gray-300"
                        }`}
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                  <span className="text-gray-600 font-medium">
                    {normalizedProduct.rating} ({normalizedProduct.reviewCount} reviews)
                  </span>
                </div>
              </div>

              <p className="mt-4 text-gray-700">{normalizedProduct.shortDesc}</p>
              
              {/* Stock Status */}
              <div className="mt-4">
                {normalizedProduct.stock > 0 ? (
                  <div className="flex items-center gap-2">
                    <span className={`flex h-2.5 w-2.5 rounded-full ${normalizedProduct.stock <= 5 ? 'bg-orange-500 animate-pulse' : 'bg-green-500'}`}></span>
                    <span className={`text-sm font-medium ${normalizedProduct.stock <= 5 ? 'text-orange-700' : 'text-green-700'}`}>
                      {normalizedProduct.stock <= 5 
                        ? `Only ${normalizedProduct.stock} left in stock!` 
                        : `In Stock: ${normalizedProduct.stock}`}
                    </span>
                  </div>
                ) : (
                  <div className="flex items-center gap-2">
                    <span className="flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    <span className="text-sm font-medium text-red-600 italic">Currently Out of Stock</span>
                  </div>
                )}
              </div>
            </div>

            {/* Color Variant */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Color
              </label>
              <div className="flex flex-wrap gap-3">
                {derivedColors.length > 0 ? (
                  derivedColors.map((color) => (
                    <button
                      key={color.id || color.color_name}
                      onClick={() => {
                        setSelectedColor(color.color_name);
                        // Jump to the first image of this color in the gallery
                        if (product.product_image_galleries) {
                          const firstImgIdx = product.product_image_galleries.findIndex(
                            img => parseInt(img.color_id) === parseInt(color.id)
                          );
                          if (firstImgIdx !== -1) setSelectedImage(firstImgIdx);
                        }
                      }}
                      className={`
                        w-10 h-10 rounded-full border-2 transition-all duration-200
                        ${
                          selectedColor === color.color_name
                            ? "border-red scale-110 shadow-md"
                            : "border-gray-300 hover:border-gray-400"
                        }
                      `}
                      style={{ 
                        backgroundColor: color.color_code?.startsWith('#') ? color.color_code : `#${color.color_code}` 
                      }}
                      title={color.color_name}
                    />
                  ))
                ) : (
                  <span className="text-gray-500 text-sm italic">No available color</span>
                )}
              </div>
            </div>

            {/* Size Variant */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Size
              </label>
              <div className="flex flex-wrap gap-2">
                {normalizedProduct.sizes.length > 0 ? (
                  normalizedProduct.sizes.map((size) => (
                    <button
                      key={size.id || size.size_name}
                      onClick={() => setSelectedSize(size.size_name)}
                      className={`
                        px-5 py-2.5 border rounded-md text-sm font-medium transition-all
                        ${
                          selectedSize === size.size_name
                            ? "border-red bg-red-50 text-red"
                            : "border-gray hover:border-red-300"
                        }
                      `}
                    >
                      {size.size_name}
                    </button>
                  ))
                ) : (
                  <span className="text-gray-500 text-sm italic">No available size</span>
                )}
              </div>
            </div>

            {/* Quantity & CTA */}
            <div className="pt-4 space-y-5">
              <div className="flex items-center gap-6">
                <label className="font-medium">Quantity:</label>
                <div className="flex border border-gray rounded-lg overflow-hidden">
                  <button
                    onClick={() => setQuantity((q) => Math.max(1, q - 1))}
                    disabled={normalizedProduct.stock <= 0}
                    className="w-12 h-11 flex items-center justify-center text-xl hover:bg-gray-100 disabled:opacity-50"
                  >
                    −
                  </button>
                  <span className="w-16 flex items-center justify-center font-medium">
                    {normalizedProduct.stock > 0 ? quantity : 0}
                  </span>
                  <button
                    onClick={() => setQuantity((q) => Math.min(normalizedProduct.stock, q + 1))}
                    disabled={normalizedProduct.stock <= 0 || quantity >= normalizedProduct.stock}
                    className="w-12 h-11 flex items-center justify-center text-xl hover:bg-gray-100 disabled:opacity-50"
                  >
                    +
                  </button>
                </div>
              </div>

              <div className="flex flex-col sm:flex-row gap-4">
                <button
                  onClick={() => handleAddToCart(false)}
                  disabled={isAdding || normalizedProduct.stock <= 0}
                  className={`
                    flex-1 py-4 rounded-lg font-bold text-lg transition-all duration-200
                    flex items-center justify-center gap-2
                    ${normalizedProduct.stock <= 0 
                      ? "bg-gray-300 text-gray-500 cursor-not-allowed" 
                      : "bg-red text-white hover:bg-red-800 active:scale-95 disabled:opacity-70"}
                  `}
                >
                  {normalizedProduct.stock <= 0 ? (
                    "Out of Stock"
                  ) : isAdding ? (
                    <span className="w-6 h-6 border-3 border-white/30 border-t-white rounded-full animate-spin" />
                  ) : (
                    "Add to Cart"
                  )}
                </button>
                <button
                  onClick={handleBuyNow}
                  disabled={isBuying || normalizedProduct.stock <= 0}
                  className={`
                    flex-1 border-2 py-4 rounded-lg font-bold text-lg active:scale-95 transition-all duration-200
                    flex items-center justify-center gap-2
                    ${normalizedProduct.stock <= 0
                      ? "border-gray-300 text-gray-400 cursor-not-allowed"
                      : "border-red text-red hover:bg-red-50"}
                  `}
                >
                  {isBuying ? (
                    <span className="w-6 h-6 border-3 border-red/30 border-t-red rounded-full animate-spin" />
                  ) : (
                    "Buy Now"
                  )}
                </button>
              </div>

              <div className="text-sm text-gray-600 space-y-1">
                <p>✓ Free shipping on orders over DKK 499</p>
                <p>✓ 30 days easy return</p>
                <p>✓ Secure payments</p>
              </div>
            </div>
          </div>
        </div>

        {/* ==================== TABS SECTION ==================== */}
        <div className="mt-12 md:mt-16">
          <div className="border-b border-gray">
            <nav className="flex flex-wrap gap-6 md:gap-10">
              {["description", "shipping"].map(
                (tab) => (
                  <button
                    key={tab}
                    onClick={() => setActiveTab(tab)}
                    className={`
                    pb-3 px-1 font-medium text-lg transition-colors
                    ${
                      activeTab === tab
                        ? "border-b-2 border-red text-red"
                        : "text-gray-600 hover:text-gray-900"
                    }
                  `}
                  >
                    {tab.charAt(0).toUpperCase() + tab.slice(1)}
                  </button>
                )
              )}
            </nav>
          </div>

          <div className="py-8">
            {activeTab === "description" && (
              <div className="prose max-w-none text-gray-700">
                <div dangerouslySetInnerHTML={{ __html: normalizedProduct.description }} />
              </div>
            )}

            {/* {activeTab === "features" && (
              <ul className="grid md:grid-cols-2 gap-4">
                {(normalizedProduct.features || []).map((feature, i) => (
                  <li key={i} className="flex items-start gap-3">
                    <span className="text-red text-xl">✓</span>
                    <span>{feature}</span>
                  </li>
                ))}
              </ul>
            )}

            {activeTab === "specification" && (
              <div className="grid md:grid-cols-2 gap-6">
                {(normalizedProduct.specifications || []).map((spec, i) => (
                  <div
                    key={i}
                    className="flex justify-between py-2 border-b border-gray"
                  >
                    <span className="font-medium text-gray-700">
                      {spec.title}
                    </span>
                    <span className="text-gray-600">{spec.value}</span>
                  </div>
                ))}
              </div>
            )} */}

            {activeTab === "shipping" && (
              <div className="space-y-4 text-gray-700">
                <p>Delivery time: 3-7 working days in Denmark & EU</p>
                <p>Free shipping on orders above DKK 499</p>
                <p>30 days hassle-free return policy</p>
                <p>Track your order easily after purchase</p>
              </div>
            )}
          </div>
        </div>

        {/* ==================== CUSTOMER REVIEWS ==================== */}
        <div className="mt-16 border-t border-gray pt-12">
          <h2 className="text-2xl md:text-3xl font-bold text-gray-900 mb-8">
            Customer Reviews ({normalizedProduct.reviewCount})
          </h2>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            {reviews.map((review) => (
              <div
                key={review.id}
                className="bg-white p-6 rounded-xl border border-gray shadow-sm"
              >
                <div className="flex items-center justify-between mb-3">
                  <div className="flex">
                    {[...Array(5)].map((_, i) => (
                      <svg
                        key={i}
                        className={`w-5 h-5 ${
                          i < review.rating
                            ? "text-yellow-400"
                            : "text-gray-300"
                        }`}
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                  <span className="text-sm text-gray-500">
                    {new Date(review.created_at).toLocaleDateString()}
                  </span>
                </div>

                <p className="text-gray-700 mb-4">"{review.comment}"</p>

                <div className="flex items-center justify-between">
                  <span className="font-medium">{review.user?.name}</span>
                  {review.verified && (
                    <span className="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                      Verified Purchase
                    </span>
                  )}
                </div>
              </div>
            ))}
          </div>

          {/* ==================== REVIEW SUBMISSION FORM ==================== */}
          <div className="bg-white p-6 md:p-8 rounded-xl border border-gray shadow-sm">
            <h3 className="text-xl md:text-2xl font-bold text-gray-900 mb-6">
              Write a Review
            </h3>

            {!auth?.user ? (
              <div className="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <div className="text-4xl mb-4 text-gray-400">🔒</div>
                <h4 className="text-lg font-semibold text-gray-700 mb-2">
                  Please login to write a review
                </h4>
                <p className="text-gray-500 mb-6 max-w-xs mx-auto">
                  Only registered customers can share their experience with our products.
                </p>
                <Link
                  href="/customer/login"
                  className="bg-red text-white px-8 py-3 rounded-lg font-medium hover:bg-red-800 transition-colors inline-block"
                >
                  Login / Register Now
                </Link>
              </div>
            ) : reviewSubmitted ? (
              <div className="text-center py-8">
                <div className="text-5xl mb-4">🎉</div>
                <h4 className="text-xl font-bold text-green-700 mb-2">
                  Thank you for your review!
                </h4>
                <p className="text-gray-600">
                  Your feedback helps other customers make better decisions. Your review is currently pending approval.
                </p>
              </div>
            ) : (
              <form onSubmit={handleReviewSubmit} className="space-y-6">
                <div>
                  <p className="text-sm text-gray-600 mb-4 italic">
                    Posting as <span className="font-semibold text-gray-900">{auth.user.name}</span>
                  </p>
                </div>
                {/* Rating Stars */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Your Rating *
                  </label>
                  <div className="flex gap-1">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <button
                        key={star}
                        type="button"
                        onClick={() => setReviewRating(star)}
                        className="focus:outline-none"
                      >
                        <svg
                          className={`w-8 h-8 transition-colors ${
                            star <= reviewRating
                              ? "text-yellow-400"
                              : "text-gray-300 hover:text-yellow-300"
                          }`}
                          fill="currentColor"
                          viewBox="0 0 20 20"
                        >
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </button>
                    ))}
                  </div>
                </div>

                {/* Review Text */}
                <div>
                  <label
                    htmlFor="reviewText"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    Your Review *
                  </label>
                  <textarea
                    id="reviewText"
                    rows={5}
                    value={reviewText}
                    onChange={(e) => setReviewText(e.target.value)}
                    className="w-full px-4 py-2 border border-gray rounded-lg focus:outline-none focus:border-red"
                    placeholder="Tell us about your experience with this product..."
                    required
                  />
                </div>

                <div className="pt-4">
                  <button
                    type="submit"
                    disabled={isSubmittingReview}
                    className={`bg-red text-white px-8 py-3 rounded-lg font-medium hover:bg-red-800 transition-colors ${isSubmittingReview ? 'opacity-70 cursor-not-allowed' : ''}`}
                  >
                    {isSubmittingReview ? 'Submitting...' : 'Submit Review'}
                  </button>
                </div>

                <p className="text-sm text-gray-500">
                  Your review will be published after moderation. We appreciate
                  honest feedback!
                </p>
              </form>
            )}
          </div>
        </div>

        {/* ==================== RELATED PRODUCTS ==================== */}
        <div className="mt-16 border-t border-gray pt-12 pb-16">
          <h2 className="text-2xl md:text-3xl font-bold text-gray-900 mb-8">
            You May Also Like
          </h2>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
            {relatedProducts.length > 0 ? (
              relatedProducts.map((item) => (
                <ProductCard key={item.id} product={item} />
              ))
            ) : (
              <p className="text-gray-500 italic col-span-full">No related products found.</p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetailsFull;