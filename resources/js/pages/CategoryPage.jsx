// CategoryPage.jsx
import React, { useState, useEffect } from "react";
import { ChevronDown } from "lucide-react";
import { Link, router, usePage } from "@inertiajs/react";
import ProductCard from "../components/ProductCard";

const CategoryPage = ({ products, category, filters, type }) => { 
  const { settings } = usePage().props;

  // --- Batch Loading Logic ---
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

    const timer = setTimeout(() => {
      setIsBatchReady(true);
    }, 2000);

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
      const routeName = type === 'category' ? 'category.products' : 
                        type === 'subcategory' ? 'subcategory.products' : 
                        'childcategory.products';
      
      router.get(route(routeName, category.slug), { ...filters, sort_by: value }, {
          preserveScroll: true,
          replace: true
      });
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Breadcrumb + Title */}
      <div className="bg-white border-b border-gray">
        <div className="container mx-auto px-4 py-4 sm:py-6">
          <nav className="text-sm text-gray-500 mb-3">
            <Link href="/" className="hover:text-red">
              Home
            </Link>
            <span className="mx-2">/</span>
            {type !== 'category' && (
                <>
                    <Link href={`/category/${category.category?.slug || '#'}`} className="hover:text-red">
                        {category.category?.name}
                    </Link>
                    <span className="mx-2">/</span>
                </>
            )}
            <span className="text-gray-900 font-medium">{category.name}</span>
          </nav>

          <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
              <h1 className="text-3xl sm:text-4xl font-bold text-gray-900">
                {category.name}
              </h1>
              <p className="mt-2 text-gray-600 max-w-2xl">
                {category.description || `Discover our collection of ${category.name} — perfect souvenirs from Copenhagen.`}
              </p>
            </div>

            <div className="flex items-center gap-4">
              <div className="relative">
                <select
                  value={filters.sort_by || "latest"}
                  onChange={(e) => handleSortChange(e.target.value)}
                  className="appearance-none bg-white border border-gray rounded-lg px-4 py-2.5 pr-10 focus:outline-none focus:ring-1 focus:ring-red outline-none"
                >
                  <option value="latest">Newest first</option>
                  <option value="lowtohigh">Price: Low to High</option>
                  <option value="hightolow">Price: High to Low</option>
                  <option value="recommended">Most Popular</option>
                </select>
                <ChevronDown
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                  size={18}
                />
              </div> 
            </div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-8 lg:py-12">
        <div>
          {/* Products Grid */}
          <div className="min-h-[400px]">
            {products.data && products.data.length > 0 ? (
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
            ) : (
                <div className="text-center py-20">
                    <p className="text-gray-500 text-lg italic">No products found in this category.</p>
                </div>
            )}

            {/* Pagination */}
            {products.links && products.links.length > 3 && (
                <div className="mt-12 flex justify-center items-center gap-2">
                  {products.links.map((link, idx) => (
                    <Link
                      key={idx}
                      href={link.url || "#"}
                      dangerouslySetInnerHTML={{ __html: link.label }}
                      className={`
                        px-4 py-2 rounded-lg border transition duration-200
                        ${link.active 
                            ? "bg-red text-white border-red" 
                            : "bg-white text-gray-700 border-gray hover:border-red"
                        }
                        ${!link.url ? "opacity-50 cursor-not-allowed" : "cursor-pointer"}
                      `}
                    />
                  ))}
                </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default CategoryPage;
