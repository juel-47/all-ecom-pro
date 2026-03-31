import React, { useState, useEffect } from "react";
import { ChevronDown } from "lucide-react";
import { Link, router, usePage } from "@inertiajs/react";
import ProductCard from "../components/ProductCard";
import Layout from "./Layout";

const AllCampaignProductsPage = ({ products, filters }) => {
  const { settings } = usePage().props;

  // --- Batch Loading Logic for Optimization ---
  const [loadedCount, setLoadedCount] = useState(0);
  const [isBatchReady, setIsBatchReady] = useState(false);
  const productsToLoad = products.data?.length || 0;

  useEffect(() => {
    setLoadedCount(0);
    setIsBatchReady(false);

    if (productsToLoad === 0) {
      setIsBatchReady(true);
      return;
    }

    // Force show after a timeout even if some images fail to report back
    const timer = setTimeout(() => {
      setIsBatchReady(true);
    }, 2500);

    return () => clearTimeout(timer);
  }, [products.data]);

  useEffect(() => {
    if (loadedCount >= productsToLoad && productsToLoad > 0) {
      setIsBatchReady(true);
    }
  }, [loadedCount, productsToLoad]);

  const handleImageLoaded = () => {
    setLoadedCount(prev => prev + 1);
  };
  // ---------------------------

  const handleSortChange = (value) => {
    router.get(route('campaign.all-products'), { ...filters, sort_by: value }, {
        preserveScroll: true,
        replace: true
    });
  };

  return (
    
      <div className="min-h-screen bg-gray-50">
        {/* Header Section */}
        <div className="bg-red text-white">
          <div className="container mx-auto px-4 py-8 md:py-16">
            <nav className="text-sm opacity-80 mb-4">
              <Link href="/" className="hover:underline">Home</Link>
              <span className="mx-2">/</span>
              <Link href="/campaign" className="hover:underline">Campaigns</Link>
              <span className="mx-2">/</span>
              <span className="font-semibold">All Campaign Products</span>
            </nav>

            <div className="flex flex-col md:flex-row justify-between items-center gap-6">
              <div className="text-center md:text-left">
                <h1 className="text-1xl md:text-2xl font-black uppercase tracking-tighter">
                  All Campaign Products
                </h1>
                <p className="mt-2 text-lg md:text-xl opacity-90 max-w-2xl">
                    Exclusive discounts on our best Danish souvenirs and authentic items. Limited time only!
                </p>
              </div>

              <div className="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20">
                <div className="text-sm uppercase tracking-widest font-bold mb-1 opacity-80">Total Deals</div>
                <div className="text-3xl font-black">{products.total}</div>
              </div>
            </div>
          </div>
        </div>

        {/* Toolbar Section */}
        <div className="bg-white border-b border-gray sticky top-0 z-20">
            <div className="container mx-auto px-4 py-4 flex items-center justify-between">
                <div className="text-sm font-medium text-gray-500">
                    Showing <span className="text-gray-900">{products.from || 0}-{products.to || 0}</span> of {products.total} products
                </div>

                <div className="flex items-center gap-4">
                    <span className="hidden sm:inline text-sm font-bold text-gray-700 uppercase tracking-wider">Sort By:</span>
                    <div className="relative">
                        <select
                        value={filters.sort_by || "latest"}
                        onChange={(e) => handleSortChange(e.target.value)}
                        className="appearance-none bg-gray-50 border border-gray rounded-xl px-4 py-2 pr-10 focus:outline-none focus:ring-1 focus:ring-red outline-none text-sm font-medium"
                        >
                            <option value="latest">Newest Arrivals</option>
                            <option value="lowtohigh">Price: Low to High</option>
                            <option value="hightolow">Price: High to Low</option>
                            <option value="recommended">Best Rated</option>
                        </select>
                        <ChevronDown
                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                        size={16}
                        />
                    </div>
                </div>
            </div>
        </div>

        <div className="container mx-auto px-4 py-10">
          {products.data && products.data.length > 0 ? (
            <>
              <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                {products.data.map((product) => (
                    <ProductCard 
                        key={product.id} 
                        product={product} 
                        onImageLoad={handleImageLoaded}
                        forceLoading={!isBatchReady}
                    />
                ))}
              </div>

              {/* Pagination */}
              {products.links && products.links.length > 3 && (
                  <div className="mt-16 flex justify-center items-center gap-2">
                  {products.links.map((link, idx) => (
                      <Link
                      key={idx}
                      href={link.url || "#"}
                      dangerouslySetInnerHTML={{ __html: link.label }}
                      className={`
                          px-5 py-2.5 rounded-xl border font-bold transition-all duration-300
                          ${link.active 
                              ? "bg-red text-white border-red shadow-lg scale-110" 
                              : "bg-white text-gray-700 border-gray hover:border-red hover:text-red shadow-sm"
                          }
                          ${!link.url ? "opacity-40 cursor-not-allowed" : "cursor-pointer"}
                      `}
                      />
                  ))}
                  </div>
              )}
            </>
          ) : (
            <div className="py-24 text-center">
              <div className="text-7xl mb-6">🏷️</div>
              <h2 className="text-3xl font-black text-gray-900 mb-4 uppercase">No Active Campaign Products</h2>
              <p className="text-gray-600 mb-10 text-lg max-w-md mx-auto">There are no discounted products available at this moment. Please check back during our next campaign!</p>
              <Link href="/" className="inline-block bg-red text-white px-10 py-4 rounded-full font-black uppercase tracking-widest shadow-xl hover:bg-red-800 transition-all">
                Back to Home
              </Link>
            </div>
          )}
        </div>
      </div>
   
  );
};

export default AllCampaignProductsPage;
