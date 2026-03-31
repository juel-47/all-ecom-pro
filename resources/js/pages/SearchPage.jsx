// SearchPage.jsx
import React, { useState, useEffect } from "react";
import Breadcrumb from "../components/Breadcrumb";
import { Link, usePage, router } from "@inertiajs/react";
import ProductCard from "../components/ProductCard";
import { route } from "ziggy-js";
import { ShoppingBag, X } from "lucide-react";

const SearchPage = ({ products, filters, categories = [], brands = [], colors = [], sizes = [], query = "" }) => {
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

  const applyFilters = (newFilters) => {
    router.get(route('product.search'), { ...newFilters, q: query }, {
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

    const updatedFilters = {
      ...filters,
      [key]: newValues.length > 0 ? newValues : undefined,
      page: 1
    };

    applyFilters(updatedFilters);
  };

  const removeFilter = (type, value) => {
      toggleFilter(type, value);
  };

  const clearAllFilters = () => {
      router.get(route('product.search'), { q: query }, {
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
    <div className="min-h-screen font-poppins bg-gray-50">
      <div className="container px-4 sm:px-6 lg:px-8 py-6 md:py-10 mx-auto">
        <Breadcrumb />

        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div className="flex flex-col gap-1">
            <h2 className="text-2xl md:text-3xl font-bold text-gray-900">
              Search Results
            </h2>
            <div className="flex items-center gap-2 text-gray-500">
                <span>Results for: <span className="font-bold text-red-600">"{query}"</span></span>
                <span className="text-gray-300">|</span>
                <span>({products.total || 0} items)</span>
            </div>
          </div>

          <div className="flex items-center gap-4 w-full md:w-auto">
            <select
              value={filters.sort_by || "latest"}
              onChange={(e) => handleSortChange(e.target.value)}
              className="border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-gray-700 text-sm md:text-base outline-none focus:ring-2 focus:ring-red-600/20"
            >
              <option value="latest">Newest First</option>
              <option value="lowtohigh">Price: Low → High</option>
              <option value="hightolow">Price: High → Low</option>
              <option value="recommended">Most Popular</option>
            </select>

            <button
              onClick={() => setShowMobileFilters(true)}
              className="lg:hidden flex items-center gap-2 bg-white border border-gray-200 px-5 py-2.5 rounded-xl font-medium"
            >
              Filters
            </button>
          </div>
        </div>

        {/* Active Filters */}
        {(Object.keys(filters).length > 1 && !(Object.keys(filters).length === 2 && filters.page && filters.q)) && (
          <div className="flex flex-wrap gap-2 mb-8">
            {categories.filter(c => isFilterActive("categories", c.id)).map((cat) => (
              <div key={cat.id} className="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-sm flex items-center gap-2 border border-red-100">
                {cat.name}
                <button onClick={() => removeFilter("categories", cat.id)} className="font-bold text-lg leading-none">×</button>
              </div>
            ))}
            {/* Brands, Colors, Sizes similar logic here */}
            {brands.filter(b => isFilterActive("brands", b.id)).map((brand) => (
              <div key={brand.id} className="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-sm flex items-center gap-2 border border-red-100">
                {brand.name}
                <button onClick={() => removeFilter("brands", brand.id)} className="font-bold text-lg leading-none">×</button>
              </div>
            ))}
            
            {colors.filter(c => isFilterActive("colors", c.id)).map((color) => (
              <div key={color.id} className="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-sm flex items-center gap-2 border border-red-100">
                <span className="w-3 h-3 rounded-full border border-gray-200" style={{ backgroundColor: color.color_code?.startsWith('#') ? color.color_code : `#${color.color_code}` }}></span>
                {color.color_name}
                <button onClick={() => removeFilter("colors", color.id)} className="font-bold text-lg leading-none">×</button>
              </div>
            ))}

            {sizes.filter(s => isFilterActive("sizes", s.id)).map((size) => (
              <div key={size.id} className="bg-red-50 text-red-600 px-4 py-1.5 rounded-full text-sm flex items-center gap-2 border border-red-100">
                Size: {size.size_name}
                <button onClick={() => removeFilter("sizes", size.id)} className="font-bold text-lg leading-none">×</button>
              </div>
            ))}

            <button onClick={clearAllFilters} className="text-gray-500 hover:text-red-600 text-sm font-medium ml-2">Clear all</button>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
          {/* Desktop Sidebar */}
          <aside className="hidden lg:block lg:col-span-3">
            <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
              <h3 className="text-lg font-bold mb-6 text-gray-900 flex items-center gap-2">
                  <span className="w-1.5 h-6 bg-red-600 rounded-full"></span>
                  Filters
              </h3>

              <div className="mb-8">
                <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Price Range</h4>
                <div className="space-y-4">
                  <input 
                    type="range" 
                    min="0" 
                    max="2000" 
                    value={activeFilters.price_max}
                    onChange={handlePriceChange}
                    onMouseUp={applyPriceFilter}
                    className="w-full accent-red-600" 
                  />
                  <div className="flex justify-between text-sm font-medium text-gray-500">
                    <span>{settings?.currency_icon || 'DKK'} 0</span>
                    <span>{settings?.currency_icon || 'DKK'} {activeFilters.price_max}+</span>
                  </div>
                </div>
              </div>

              <div className="mb-8">
                <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Categories</h4>
                <div className="space-y-2.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                  {categories.map((cat) => (
                    <label key={cat.id} className="flex items-center gap-2 cursor-pointer group">
                      <input
                        type="checkbox"
                        checked={isFilterActive("categories", cat.id)}
                        onChange={() => toggleFilter("categories", cat.id)}
                        className="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-600"
                      />
                      <span className="text-gray-600 group-hover:text-red-600 transition-colors">{cat.name}</span>
                    </label>
                  ))}
                </div>
              </div>

              {/* Brands, Colors, Sizes repeated design */}
              {brands.length > 0 && (
                <div className="mb-8">
                    <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Brands</h4>
                    <div className="space-y-2.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                    {brands.map((brand) => (
                        <label key={brand.id} className="flex items-center gap-2 cursor-pointer group">
                        <input
                            type="checkbox"
                            checked={isFilterActive("brands", brand.id)}
                            onChange={() => toggleFilter("brands", brand.id)}
                            className="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-600"
                        />
                        <span className="text-gray-600 group-hover:text-red-600 transition-colors">{brand.name}</span>
                        </label>
                    ))}
                    </div>
                </div>
              )}

              {/* Colors */}
              {colors.length > 0 && (
                <div className="mb-8">
                  <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Colors</h4>
                  <div className="flex flex-wrap gap-2.5">
                    {colors.map((c) => (
                      <button
                        key={c.id}
                        onClick={() => toggleFilter("colors", c.id)}
                        className={`w-8 h-8 rounded-full border-2 transition-all p-0.5 ${
                          isFilterActive("colors", c.id)
                            ? "border-red-600 ring-2 ring-red-600/20 scale-110"
                            : "border-gray-200 hover:border-gray-300"
                        }`}
                        title={c.color_name}
                      >
                        <div 
                          className="w-full h-full rounded-full shadow-inner"
                          style={{ backgroundColor: c.color_code?.startsWith('#') ? c.color_code : `#${c.color_code}` }}
                        ></div>
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Sizes */}
              {sizes.length > 0 && (
                <div>
                  <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Sizes</h4>
                  <div className="grid grid-cols-4 gap-2">
                    {sizes.map((s) => (
                      <button
                        key={s.id}
                        onClick={() => toggleFilter("sizes", s.id)}
                        className={`border rounded-lg py-2 text-xs font-bold transition-all ${
                          isFilterActive("sizes", s.id)
                            ? "border-red-600 bg-red-50 text-red-600 shadow-sm"
                            : "border-gray-200 text-gray-600 hover:border-red-600 hover:text-red-600"
                        }`}
                      >
                        {s.size_name}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </aside>

          {/* Products Grid */}
          <main className="lg:col-span-9">
            {sortedProducts.length > 0 ? (
                <>
                <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
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
                        className={`px-4 py-2 rounded-xl transition-all duration-200 text-sm font-semibold border ${
                            link.active 
                            ? "bg-red-600 text-white border-red-600 shadow-lg shadow-red-600/20" 
                            : "bg-white text-gray-600 border-gray-200 hover:border-red-600 hover:text-red-600"
                        } ${!link.url ? "opacity-50 cursor-not-allowed" : "cursor-pointer"}`}
                        />
                    ))}
                    </div>
                )}
                </>
            ) : (
                <div className="bg-white rounded-2xl p-20 text-center border border-gray-100 shadow-sm">
                    <div className="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <ShoppingBag size={32} className="text-gray-300" />
                    </div>
                    <h3 className="text-xl font-bold text-gray-900 mb-2">No results found</h3>
                    <p className="text-gray-500 max-w-xs mx-auto mb-8">
                        We couldn't find any products matching your search for <span className="font-bold">"{query}"</span>.
                    </p>
                    <Link href="/all-products" className="inline-flex items-center justify-center px-8 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25">
                        Browse All Products
                    </Link>
                </div>
            )}
          </main>
        </div>
      </div>
      
        {/* Mobile Filters Modal */}
        {showMobileFilters && (
          <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-100 flex items-end md:hidden animate-in fade-in duration-300">
            <div className="bg-white w-full rounded-t-3xl max-h-[85vh] overflow-hidden flex flex-col animate-in slide-in-from-bottom duration-300 shadow-2xl">
              <div className="p-6 border-b border-gray-100 flex justify-between items-center">
                <div className="flex items-center gap-2">
                    <span className="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    <h3 className="text-xl font-bold text-gray-900">Filters</h3>
                </div>
                <button
                  onClick={() => setShowMobileFilters(false)}
                  className="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-100"
                >
                  <X size={20} />
                </button>
              </div>

              <div className="p-6 flex-1 overflow-y-auto custom-scrollbar space-y-8">
                {/* Categories */}
                <div>
                  <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Categories</h4>
                  <div className="grid grid-cols-1 gap-3">
                    {categories.map((cat) => (
                      <label key={cat.id} className="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-red-100 transition-colors">
                        <input
                          type="checkbox"
                          checked={isFilterActive("categories", cat.id)}
                          onChange={() => toggleFilter("categories", cat.id)}
                          className="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-600"
                        />
                        <span className="text-gray-700 font-medium">{cat.name}</span>
                      </label>
                    ))}
                  </div>
                </div>

                {/* Brands */}
                {brands.length > 0 && (
                  <div>
                    <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Brands</h4>
                    <div className="grid grid-cols-1 gap-3">
                      {brands.map((brand) => (
                        <label key={brand.id} className="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-red-100 transition-colors">
                          <input
                            type="checkbox"
                            checked={isFilterActive("brands", brand.id)}
                            onChange={() => toggleFilter("brands", brand.id)}
                            className="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-600"
                          />
                          <span className="text-gray-700 font-medium">{brand.name}</span>
                        </label>
                      ))}
                    </div>
                  </div>
                )}
  
                {/* Colors */}
                {colors.length > 0 && (
                    <div>
                    <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Colors</h4>
                    <div className="flex flex-wrap gap-3">
                        {colors.map((c) => (
                        <button
                            key={c.id}
                            onClick={() => toggleFilter("colors", c.id)}
                            className={`w-12 h-12 rounded-full border-2 transition-all p-1 ${
                            isFilterActive("colors", c.id)
                                ? "border-red-600 ring-2 ring-red-600/20 shadow-lg"
                                : "border-gray-200"
                            }`}
                        >
                            <div 
                                className="w-full h-full rounded-full" 
                                style={{ backgroundColor: c.color_code?.startsWith('#') ? c.color_code : `#${c.color_code}` }}
                            ></div>
                        </button>
                        ))}
                    </div>
                    </div>
                )}

                {/* Sizes */}
                {sizes.length > 0 && (
                    <div>
                    <h4 className="font-bold text-sm text-gray-800 mb-4 uppercase tracking-wider">Sizes</h4>
                    <div className="grid grid-cols-4 gap-3">
                        {sizes.map((s) => (
                        <button
                            key={s.id}
                            onClick={() => toggleFilter("sizes", s.id)}
                            className={`border rounded-xl py-4 text-sm font-bold transition-all ${
                            isFilterActive("sizes", s.id)
                                ? "border-red-600 bg-red-50 text-red-600"
                                : "border-gray-200 text-gray-600"
                            }`}
                        >
                            {s.size_name}
                        </button>
                        ))}
                    </div>
                    </div>
                )}
              </div>

              <div className="p-6 border-t border-gray-100 bg-gray-50 flex gap-4">
                <button
                  onClick={clearAllFilters}
                  className="flex-1 bg-white border border-gray-200 py-4 rounded-2xl font-bold text-gray-700 hover:bg-gray-50 transition-colors"
                >
                  Clear All
                </button>
                <button
                  onClick={() => setShowMobileFilters(false)}
                  className="flex-1 bg-red-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-red-600/20 hover:bg-red-700 transition-colors"
                >
                  Apply Filters
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
  );
};

export default SearchPage;
