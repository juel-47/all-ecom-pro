// ShopPage.jsx
import React, { useState, useEffect } from "react";
import Breadcrumb from "../components/Breadcrumb";
import { Link, usePage, router } from "@inertiajs/react";
import ProductCard from "../components/ProductCard";
import { route } from "ziggy-js";

const ProductPage = ({ products, filters, categories = [], brands = [], colors = [], sizes = [] }) => {
  const { settings } = usePage().props;
  
  const [activeFilters, setActiveFilters] = useState({
    price_min: filters.min_price || 0,
    price_max: filters.max_price || 2000,
    colors: filters.color_ids || [],
    sizes: filters.size_ids || [],
    categories: filters.category_ids || [],
    brands: filters.brand_ids || [],
    sort: filters.sort_by || "latest",
  });

  const [showMobileFilters, setShowMobileFilters] = useState(false);

  // --- Batch Loading Logic ---
  const [loadedCount, setLoadedCount] = useState(0);
  const [isBatchReady, setIsBatchReady] = useState(false);
  const productsToLoad = products.data?.length || 0;

  useEffect(() => {
    // Reset loading state when products change (e.g. pagination or filters)
    setLoadedCount(0);
    setIsBatchReady(false);

    if (productsToLoad === 0) {
      setIsBatchReady(true);
      return;
    }

    // Backup timeout: reveal after 2s if some images are slow or fail
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

  // Helper to check if a filter value is active
  const isFilterActive = (type, id) => {
    const key = type === 'categories' ? 'category_ids' : 
                type === 'brands' ? 'brand_ids' : 
                type === 'colors' ? 'color_ids' : 
                type === 'sizes' ? 'size_ids' : type;
    
    const value = filters[key];
    if (!value) return false;
    
    if (Array.isArray(value)) {
      return value.map(String).includes(String(id));
    }
    return String(value) === String(id);
  };

  // Sync activeFilters (local state) with props (URL filters)
  useEffect(() => {
    setActiveFilters(prev => ({
      ...prev,
      price_min: filters.min_price || 0,
      price_max: filters.max_price || 2000,
      colors: Array.isArray(filters.color_ids) ? filters.color_ids : (filters.color_ids ? [filters.color_ids] : []),
      sizes: Array.isArray(filters.size_ids) ? filters.size_ids : (filters.size_ids ? [filters.size_ids] : []),
      categories: Array.isArray(filters.category_ids) ? filters.category_ids : (filters.category_ids ? [filters.category_ids] : []),
      brands: Array.isArray(filters.brand_ids) ? filters.brand_ids : (filters.brand_ids ? [filters.brand_ids] : []),
      sort: filters.sort_by || "latest",
    }));
  }, [filters]);

  // Helper to trigger Inertia request for filtering
  const applyFilters = (newFilters) => {
    router.get(route('all.products'), newFilters, {
      preserveScroll: true,
      preserveState: true,
      replace: true
    });
  };

  const toggleFilter = (type, value) => {
    const key = type === 'categories' ? 'category_ids' : 
                type === 'brands' ? 'brand_ids' : 
                type === 'colors' ? 'color_ids' : 
                type === 'sizes' ? 'size_ids' : type;

    const rawValue = filters[key] || [];
    const currentValues = Array.isArray(rawValue) ? rawValue.map(String) : [String(rawValue)];
    const strValue = String(value);

    let newValues;
    if (currentValues.includes(strValue)) {
      newValues = currentValues.filter((v) => v !== strValue);
    } else {
      newValues = [...currentValues, strValue];
    }

    // If newValues is empty, we should remove the key entirely or send empty array
    // Laravel/Inertia handles empty array as removing the param usually
    const updatedFilters = {
      ...filters,
      [key]: newValues.length > 0 ? newValues : undefined,
      page: 1 // Reset pagination on filter change
    };

    applyFilters(updatedFilters);
  };

  const removeFilter = (type, value) => {
      toggleFilter(type, value);
  };

  const clearAllFilters = () => {
      router.get(route('all.products'), {}, {
          preserveScroll: true,
          replace: true
      });
  };

  const handleSortChange = (value) => {
      applyFilters({ ...filters, sort_by: value, page: 1 });
  };

  const handlePriceChange = (e) => {
      const value = e.target.value;
      setActiveFilters(prev => ({ ...prev, price_max: value }));
  };

  const applyPriceFilter = () => {
      applyFilters({ ...filters, max_price: activeFilters.price_max, page: 1 });
  };

  const sortedProducts = products.data || [];

  return (
    <div className="min-h-screen">
      <div className="container px-4 sm:px-6 lg:px-8 py-6 md:py-10">
        {/* Breadcrumb */}
        <Breadcrumb
          customItems={[
            { name: "All Products" },
          ]}
        />

        {/* ==================== FILTER & SORT BAR ==================== */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
          <div className="flex items-center gap-4">
            <h2 className="text-xl md:text-2xl font-bold text-gray-900">
              All Products
            </h2>
            <span className="text-gray-600">
              ({products.total || 0} items)
            </span>
          </div>

          <div className="flex items-center gap-4 w-full md:w-auto">
            <select
              value={filters.sort_by || "latest"}
              onChange={(e) => handleSortChange(e.target.value)}
              className="border border-gray rounded-lg px-4 py-2 bg-white text-gray-700 text-sm md:text-base outline-0"
            >
              <option value="latest">Newest First</option>
              <option value="lowtohigh">Price: Low → High</option>
              <option value="hightolow">Price: High → Low</option>
              <option value="recommended">Most Popular</option>
            </select>

            <button
              onClick={() => setShowMobileFilters(true)}
              className="md:hidden bg-white border border-gray px-5 py-2 rounded-lg font-medium"
            >
              Filters
            </button>
          </div>
        </div>

        {/* Active Filters */}
        {(Object.keys(filters).length > 0 && !(Object.keys(filters).length === 1 && filters.page)) && (
          <div className="flex flex-wrap gap-2 mb-6">
            {categories.filter(c => isFilterActive("categories", c.id)).map((cat) => (
              <div
                key={cat.id}
                className="bg-red-50 text-red px-4 py-1.5 rounded-full text-sm flex items-center gap-2"
              >
                {cat.name}
                <button
                  onClick={() => removeFilter("categories", cat.id)}
                  className="font-bold text-lg leading-none"
                >
                  ×
                </button>
              </div>
            ))}

            {brands.filter(b => isFilterActive("brands", b.id)).map((brand) => (
              <div
                key={brand.id}
                className="bg-red-50 text-red px-4 py-1.5 rounded-full text-sm flex items-center gap-2"
              >
                {brand.name}
                <button
                  onClick={() => removeFilter("brands", brand.id)}
                  className="font-bold text-lg leading-none"
                >
                  ×
                </button>
              </div>
            ))}

            {colors.filter(c => isFilterActive("colors", c.id)).map((color) => (
              <div
                key={color.id}
                className="bg-red-50 text-red px-4 py-1.5 rounded-full text-sm flex items-center gap-2"
              >
                {color.color_name}
                <button
                  onClick={() => removeFilter("colors", color.id)}
                  className="font-bold text-lg leading-none"
                >
                  ×
                </button>
              </div>
            ))}

            {sizes.filter(s => isFilterActive("sizes", s.id)).map((size) => (
              <div
                key={size.id}
                className="bg-red-50 text-red px-4 py-1.5 rounded-full text-sm flex items-center gap-2"
              >
                Size {size.size_name}
                <button
                  onClick={() => removeFilter("sizes", size.id)}
                  className="font-bold text-lg leading-none"
                >
                  ×
                </button>
              </div>
            ))}

            <button
              onClick={clearAllFilters}
              className="text-gray-600 hover:text-red text-sm underline ml-2"
            >
              Clear all
            </button>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
          {/* Desktop Sidebar */}
          <aside className="hidden lg:block lg:col-span-3">
            <div className="bg-white p-6 rounded-xl border border-gray sticky top-6">
              <h3 className="text-lg font-bold mb-6">Filters</h3>

              {/* Price Range */}
              <div className="mb-8">
                <h4 className="font-medium mb-4">Price Range</h4>
                <div className="space-y-4">
                  <input 
                    type="range" 
                    min="0" 
                    max="2000" 
                    value={activeFilters.price_max}
                    onChange={handlePriceChange}
                    onMouseUp={applyPriceFilter}
                    onTouchEnd={applyPriceFilter}
                    className="w-full accent-red" 
                  />
                  <div className="flex justify-between text-sm text-gray-600">
                    <span>{settings?.currency_icon || '€'}0</span>
                    <span>{settings?.currency_icon || '€'}{activeFilters.price_max}+</span>
                  </div>
                </div>
              </div>

              {/* Categories - Multi Select */}
              <div className="mb-8">
                <h4 className="font-medium mb-4">Categories</h4>
                <div className="space-y-2">
                  {categories.map((cat) => (
                    <label
                      key={cat.id}
                      className="flex items-center gap-2 cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        checked={isFilterActive("categories", cat.id)}
                        onChange={() => toggleFilter("categories", cat.id)}
                        className="h-4 w-4 text-red border-gray-300 rounded"
                      />
                      <span className="text-gray-700">{cat.name}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Brands - Multi Select */}
              <div className="mb-8">
                <h4 className="font-medium mb-4">Brands</h4>
                <div className="space-y-2">
                  {brands.map((brand) => (
                    <label
                      key={brand.id}
                      className="flex items-center gap-2 cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        checked={isFilterActive("brands", brand.id)}
                        onChange={() => toggleFilter("brands", brand.id)}
                        className="h-4 w-4 text-red border-gray-300 rounded"
                      />
                      <span className="text-gray-700">{brand.name}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Colors */}
              <div className="mb-8">
                <h4 className="font-medium mb-4">Colors</h4>
                <div className="flex flex-wrap gap-3">
                  {colors.map((c) => (
                    <button
                      key={c.id}
                      onClick={() => toggleFilter("colors", c.id)}
                      className={`w-10 h-10 rounded-full border-2 transition-all ${
                        isFilterActive("colors", c.id)
                          ? "border-red scale-110 shadow-md"
                          : "border-gray-300 hover:border-gray-400"
                      }`}
                      style={{
                        backgroundColor: c.color_code?.startsWith('#') ? c.color_code : `#${c.color_code}`
                      }}
                      title={c.color_name}
                    />
                  ))}
                </div>
              </div>

              {/* Sizes */}
              <div>
                <h4 className="font-medium mb-4">Sizes</h4>
                <div className="grid grid-cols-5 gap-2">
                  {sizes.map((s) => (
                    <button
                      key={s.id}
                      onClick={() => toggleFilter("sizes", s.id)}
                      className={`border rounded-md py-2 text-sm font-medium transition-all ${
                        isFilterActive("sizes", s.id)
                          ? "border-red bg-red-50 text-red"
                          : "border-gray hover:border-red"
                      }`}
                    >
                      {s.size_name}
                    </button>
                  ))}
                </div>
              </div>
            </div>
          </aside>

          {/* Products Grid */}
          <main className="lg:col-span-9">
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-6">
              {sortedProducts.map((product) => (
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
                <div className="mt-12 flex flex-wrap justify-center items-center gap-2">
                  {products.links.map((link, idx) => (
                    <Link
                      key={idx}
                      href={link.url || "#"}
                      dangerouslySetInnerHTML={{ __html: link.label }}
                      className={`
                        px-4 py-2 rounded-lg border transition duration-200 text-sm font-medium
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
          </main>
        </div>

        {/* Mobile Filters */}
        {showMobileFilters && (
          <div className="fixed inset-0 bg-black/50 z-50 flex items-end md:hidden">
            <div className="bg-white w-full rounded-t-2xl max-h-[85vh] overflow-y-auto">
              <div className="p-5 border-b border-gray flex justify-between items-center">
                <h3 className="text-xl font-bold">Filters</h3>
                <button
                  onClick={() => setShowMobileFilters(false)}
                  className="text-2xl font-bold"
                >
                  ×
                </button>
              </div>

              <div className="p-5 space-y-8">
                {/* Categories */}
                <div>
                  <h4 className="font-medium mb-4">Categories</h4>
                  <div className="space-y-3">
                    {categories.map((cat) => (
                      <label
                        key={cat.id}
                        className="flex items-center gap-2 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          checked={isFilterActive("categories", cat.id)}
                          onChange={() => toggleFilter("categories", cat.id)}
                          className="h-5 w-5 text-red border-gray-300 rounded"
                        />
                        <span>{cat.name}</span>
                      </label>
                    ))}
                  </div>
                </div>

                {/* Brands */}
                <div>
                  <h4 className="font-medium mb-4">Brands</h4>
                  <div className="space-y-3">
                    {brands.map((brand) => (
                      <label
                        key={brand.id}
                        className="flex items-center gap-2 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          checked={isFilterActive("brands", brand.id)}
                          onChange={() => toggleFilter("brands", brand.id)}
                          className="h-5 w-5 text-red border-gray-300 rounded"
                        />
                        <span>{brand.name}</span>
                      </label>
                    ))}
                  </div>
                </div>
 
                {/* Colors */}
                <div>
                  <h4 className="font-medium mb-4">Colors</h4>
                  <div className="flex flex-wrap gap-3">
                    {colors.map((c) => (
                      <button
                        key={c.id}
                        onClick={() => toggleFilter("colors", c.id)}
                        className={`w-12 h-12 rounded-full border-2 transition-all ${
                          isFilterActive("colors", c.id)
                            ? "border-red scale-110 shadow-md"
                            : "border-gray-300 hover:border-gray-400"
                        }`}
                        style={{
                          backgroundColor: c.color_code?.startsWith('#') ? c.color_code : `#${c.color_code}`
                        }}
                      />
                    ))}
                  </div>
                </div>

                {/* Sizes */}
                <div>
                  <h4 className="font-medium mb-4">Sizes</h4>
                  <div className="grid grid-cols-5 gap-3">
                    {sizes.map((s) => (
                      <button
                        key={s.id}
                        onClick={() => toggleFilter("sizes", s.id)}
                        className={`border rounded-md py-3 text-base font-medium transition-all ${
                          isFilterActive("sizes", s.id)
                            ? "border-red bg-red-50 text-red"
                            : "border-gray hover:border-red"
                        }`}
                      >
                        {s.size_name}
                      </button>
                    ))}
                  </div>
                </div>
              </div>

              <div className="p-5 border-t border-gray flex gap-4">
                <button
                  onClick={clearAllFilters}
                  className="flex-1 border border-gray-400 py-3 rounded-lg font-medium"
                >
                  Clear
                </button>
                <button
                  onClick={() => setShowMobileFilters(false)}
                  className="flex-1 bg-red text-white py-3 rounded-lg font-medium"
                >
                  Apply Filters
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Trust Badges */}
        <div className="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 py-10 border-t border-gray-200">
             <div className="flex flex-col items-center text-center">
                 <div className="w-12 h-12 bg-red-50 text-red flex items-center justify-center rounded-full mb-4">
                   <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                 </div>
                 <h4 className="font-bold text-gray-900 mb-1">Fast Shipping</h4>
                 <p className="text-sm text-gray-500">World-wide delivery</p>
             </div>
             <div className="flex flex-col items-center text-center">
                 <div className="w-12 h-12 bg-red-50 text-red flex items-center justify-center rounded-full mb-4">
                   <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                 </div>
                 <h4 className="font-bold text-gray-900 mb-1">Secure Payment</h4>
                 <p className="text-sm text-gray-500">100% Secure Transaction</p>
             </div>
             <div className="flex flex-col items-center text-center">
                 <div className="w-12 h-12 bg-red-50 text-red flex items-center justify-center rounded-full mb-4">
                 <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
                 </div>
                 <h4 className="font-bold text-gray-900 mb-1">Easy Returns</h4>
                 <p className="text-sm text-gray-500">30 Days Money Back</p>
             </div>
             <div className="flex flex-col items-center text-center">
                 <div className="w-12 h-12 bg-red-50 text-red flex items-center justify-center rounded-full mb-4">
                   <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                 </div>
                 <h4 className="font-bold text-gray-900 mb-1">24/7 Support</h4>
                 <p className="text-sm text-gray-500">Dedicated Customer Help</p>
             </div>
        </div>
      </div>
    </div>
  );
};

export default ProductPage;