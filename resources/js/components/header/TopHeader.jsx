 
import React, { useState, useEffect, useRef } from "react";
import { Search, User, ShoppingCart, Loader2, X as CloseIcon, ChevronRight } from "lucide-react";
import { IoCart } from "react-icons/io5";
import { FaUser } from "react-icons/fa";
import { Link, usePage, router } from "@inertiajs/react";
import useCartStore from "../../stores/cartStore";
import axios from "axios";

const TopHeader = () => {
  const { auth, cart, settings } = usePage().props;
  const { cartCount } = useCartStore();
  
  const [query, setQuery] = useState("");
  const [mobileQuery, setMobileQuery] = useState("");
  const [results, setResults] = useState([]);
  const [isSearching, setIsSearching] = useState(false);
  const [showDropdown, setShowDropdown] = useState(false);
  const dropdownRef = useRef(null);
  const searchInputRef = useRef(null);

  // Use a effect to sync mobile and desktop queries
  useEffect(() => {
    if (query !== mobileQuery) {
        // sync if needed or just use one state
    }
  }, [query, mobileQuery]);

  // Debounced search effect (using query as single source of truth for search)
  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      const activeQuery = query || mobileQuery;
      if (activeQuery.length >= 2) {
        searchProducts(activeQuery);
      } else {
        setResults([]);
        setShowDropdown(false);
      }
    }, 400);

    return () => clearTimeout(delayDebounceFn);
  }, [query, mobileQuery]);

  // Close dropdown on click outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target) && 
          searchInputRef.current && !searchInputRef.current.contains(event.target)) {
        setShowDropdown(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const searchProducts = async (keyword) => {
    setIsSearching(true);
    try {
      const response = await axios.get(`/api/live-search?q=${keyword}`);
      setResults(response.data);
      setShowDropdown(true);
    } catch (error) {
      console.error("Search error:", error);
    } finally {
      setIsSearching(false);
    }
  };

  const handleSearch = (e, customQuery) => {
    if (e) e.preventDefault();
    const activeQuery = customQuery || query || mobileQuery;
    if (activeQuery.trim()) {
      setShowDropdown(false);
      router.get('/product-search', { q: activeQuery });
    }
  };

  return (
    <header className="bg-red-700 text-white shadow-md">
      <div className="container px-4 sm:px-6 lg:px-8 mx-auto">
        <div className="flex items-center justify-between h-16 md:h-20">
          {/* Logo / Brand Name */}
          <div className="shrink-0">
            <Link href="/">
              <h1 className="text-md md:text-3xl font-bold tracking-tight font-pop">
                DANISH SOUVENIRS
              </h1>
            </Link>
          </div>

          {/* Search Bar - center on desktop, full width on mobile */}
          <div className="hidden md:block flex-1 max-w-xl mx-4 md:mx-8 relative">
            <form onSubmit={(e) => handleSearch(e, query)} className="relative z-50">
              <input
                ref={searchInputRef}
                type="text"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                onFocus={() => query.length >= 2 && setShowDropdown(true)}
                placeholder="Search product..."
                className="
                  w-full
                  bg-white
                  text-gray-800 
                  placeholder:text-gray-400
                  pl-10 pr-12
                  py-2.5 md:py-3
                  rounded-full
                  border border-transparent
                  focus:outline-none focus:ring-4 focus:ring-white/20
                  transition-all duration-200 font-pop shadow-inner
                "
              />
              <div className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                {isSearching ? <Loader2 size={18} className="animate-spin" /> : <Search size={18} />}
              </div>
              
              {query && (
                <button 
                  type="button"
                  onClick={() => {setQuery(""); setResults([]); setShowDropdown(false);}}
                  className="absolute right-14 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600"
                  aria-label="Clear search"
                >
                  <CloseIcon size={16} />
                </button>
              )}

              <button
                type="submit"
                className="
                  absolute right-1.5 top-1/2 -translate-y-1/2
                  p-2.5 rounded-full
                  bg-red-700 hover:bg-red-800
                  cursor-pointer
                  transition-all shadow-md active:scale-95
                "
                aria-label="Search"
              >
                <Search size={18} className="text-white" />
              </button>
            </form>

            {/* Live Search Results Dropdown */}
            {showDropdown && results.length > 0 && (
              <div 
                ref={dropdownRef}
                className="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 animate-in fade-in slide-in-from-top-2 duration-200"
              >
                <div className="max-h-[450px] overflow-y-auto custom-scrollbar">
                  <div className="px-5 py-3 border-b border-gray-50 flex items-center justify-between">
                    <span className="text-xs font-bold text-gray-400 uppercase tracking-wider">Top Results</span>
                    <span className="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold">{results.length} found</span>
                  </div>
                  {results.map((product) => (
                    <Link
                      key={product.id}
                      href={`/product-details/${product.slug}`}
                      onClick={() => setShowDropdown(false)}
                      className="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group"
                    >
                      <div className="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 shrink-0 border border-gray-100 group-hover:border-red-200 transition-colors">
                        <img 
                          src={product.thumb_image} 
                          alt={product.name} 
                          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" 
                        />
                      </div>
                      <div className="flex-1 min-w-0">
                        <h4 className="text-sm font-bold text-gray-900 group-hover:text-red-700 transition-colors truncate">{product.name}</h4>
                        <div className="flex items-center gap-2 mt-1">
                          <span className="text-red-600 font-bold text-sm">
                            {settings?.currency_icon || 'DKK'}{product.offer_price || product.price}
                          </span>
                          {product.offer_price && (
                            <span className="text-xs text-gray-400 line-through">
                              {settings?.currency_icon || 'DKK'}{product.price}
                            </span>
                          )}
                        </div>
                      </div>
                      <div className="text-gray-300 group-hover:text-red-600 transition-colors">
                        <ChevronRight size={18} />
                      </div>
                    </Link>
                  ))}
                </div>
                {query && (
                  <button 
                    onClick={(e) => handleSearch(e, query)}
                    className="w-full py-4 px-5 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-bold transition-colors flex items-center justify-center gap-2 border-t border-red-50"
                  >
                    View all results for "{query}" 
                    <Search size={14} />
                  </button>
                )}
              </div>
            )}
            
            {showDropdown && results.length === 0 && query.length >= 2 && !isSearching && (
              <div 
                ref={dropdownRef}
                className="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 p-8 text-center z-50"
              >
                <div className="bg-gray-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                  <Search size={20} className="text-gray-300" />
                </div>
                <p className="text-gray-500 text-sm">No products found for <span className="font-bold">"{query}"</span></p>
              </div>
            )}
          </div>

          {/* Icons - right side */}
          <div className="flex items-center gap-4 md:gap-6">
            {auth?.user ? (
               <Link
                href="/user-profile"
                className="flex items-center gap-2 p-2 hover:bg-white/10 rounded-lg transition-colors"
                aria-label="User account"
              >
                <FaUser size={30} />
                <span className="hidden sm:inline font-medium text-sm font-pop uppercase tracking-wider">{auth.user.name}</span>
              </Link>
            ) : (
              <Link
                href="/customer/login"
                className="flex items-center gap-2 p-2 hover:bg-white/10 rounded-lg transition-colors"
                aria-label="Login"
              >
                <FaUser size={30} />
                <span className="hidden sm:inline font-medium text-sm font-pop uppercase tracking-wider">Login</span>
              </Link>
            )}

            <Link
              href="/cart"
              className="p-2 hover:bg-white/10 rounded-full transition-colors relative"
              aria-label="Shopping cart"
            >
              <IoCart size={32} />
              {cartCount > 0 && (
                <span className="absolute -top-1 -right-1 bg-white text-red-700 text-xs font-bold rounded-full px-1.5 min-w-[1.2rem] h-5 flex items-center justify-center border border-red-700">
                  {cartCount}
                </span>
              )}
            </Link>
          </div>
        </div>

        {/* Mobile Search Bar - visible only on mobile */}
        <div className="md:hidden pb-4 px-4 w-full relative">
            <form onSubmit={(e) => handleSearch(e, mobileQuery)} className="relative z-50">
              <input
                type="text"
                value={mobileQuery}
                onChange={(e) => setMobileQuery(e.target.value)}
                onFocus={() => mobileQuery.length >= 2 && setShowDropdown(true)}
                placeholder="Search products..."
                className="
                  w-full
                  bg-white
                  text-gray-800 
                  placeholder:text-gray-400
                  pl-10 pr-12
                  py-2.5
                  rounded-full
                  border border-transparent
                  focus:outline-none focus:ring-4 focus:ring-white/20
                  transition-all duration-200 font-pop shadow-inner
                "
              />
              <div className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                {isSearching ? <Loader2 size={16} className="animate-spin" /> : <Search size={16} />}
              </div>
              
              <button
                type="submit"
                className="
                  absolute right-1 top-1/2 -translate-y-1/2
                  p-2 rounded-full
                  bg-red-700
                  cursor-pointer
                "
              >
                <Search size={16} className="text-white" />
              </button>
            </form>

            {/* Mobile Dropdown Results */}
            {showDropdown && results.length > 0 && mobileQuery.length >= 2 && (
              <div 
                ref={dropdownRef}
                className="absolute top-full left-4 right-4 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50"
              >
                  {results.map((product) => (
                    <Link
                      key={product.id}
                      href={`/product-details/${product.slug}`}
                      onClick={() => {setShowDropdown(false); setMobileQuery("");}}
                      className="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0"
                    >
                      <img src={product.thumb_image} alt={product.name} className="w-10 h-10 rounded-lg object-cover" />
                      <div className="flex-1 min-w-0">
                        <h4 className="text-xs font-bold text-gray-900 truncate">{product.name}</h4>
                        <span className="text-red-600 font-bold text-xs">{settings?.currency_icon || 'DKK'}{product.offer_price || product.price}</span>
                      </div>
                    </Link>
                  ))}
                  <button 
                    onClick={(e) => handleSearch(e, mobileQuery)}
                    className="w-full py-3 bg-red-50 text-red-700 text-xs font-bold text-center block"
                  >
                    View all results
                  </button>
              </div>
            )}
        </div>
      </div>
    </header>
  );
};

export default TopHeader;
