// Header.jsx
import React, { useState } from "react";
import {
  ChevronDown,
  ChevronRight,
  Menu,
  X,
  Home,
  ShoppingBag,
  Gift,
} from "lucide-react";
import TopHeader from "./TopHeader";
import { Link, usePage } from "@inertiajs/react";

const Header = () => {
    const { categoriess, footerInfo } = usePage().props;
    const [isMobileOpen, setIsMobileOpen] = useState(false);
    const [activeCategory, setActiveCategory] = useState(null);
    const [openMobileMenus, setOpenMobileMenus] = useState({});
    
    // Hotline from DB or fallback
    const hotline = footerInfo?.phone || '+45 55 37 13 518';

    // Ultra-robust data mapping
    const dynamicCategories = (categoriess || []).map(cat => {
        const subList = cat.sub_categories || cat.subCategories || cat.subcategories || cat.children || [];
        return {
            title: cat.name || 'Category',
            slug: cat.slug || 'none',
            icon: Gift,
            items: subList.map(sub => {
                const childList = sub.child_categories || sub.childCategories || sub.childcategories || sub.children || sub.sub || [];
                return {
                    name: sub.name || 'Subcategory',
                    slug: sub.slug || 'none',
                    sub: childList.map(child => ({
                        name: typeof child === 'string' ? child : (child.name || 'Child'),
                        slug: typeof child === 'string' ? '#' : (child.slug || 'none')
                    }))
                };
            })
        };
    });

    const navItems = [
        { label: "HOME", path: "/", icon: Home },
        {
            label: "PRODUCTS",
            path: "/all-products",
            hasMegaMenu: true,
            icon: ShoppingBag,
            categories: dynamicCategories
        },
        { label: "CAMPAIGN", path: "/campaign", icon: Gift },
        { label: "BLOG", path: "/blog", icon: null },
        { label: "LOCATE STORE", path: "/locate-store", icon: null },
    ];

    const toggleMobileMenu = (key) => {
        setOpenMobileMenus(prev => ({ ...prev, [key]: !prev[key] }));
    };

    return (
        <header className="sticky top-0 z-50 bg-white shadow-sm border-b border-b-black/50 font-poppins">
            <TopHeader />

            <nav className="bg-white">
                <div className="container mx-auto px-4">
                    {/* Desktop Navigation */}
                    <div className="hidden lg:flex items-center justify-between h-12">
                        <div className="flex items-center space-x-10 h-full">
                            {navItems.map((item, index) => (
                                <div
                                    key={index}
                                    className="relative group h-full flex items-center"
                                    onMouseEnter={() => {
                                        if (item.hasMegaMenu && item.categories.length > 0) {
                                            setActiveCategory(activeCategory || item.categories[0].slug);
                                        }
                                    }}
                                    onMouseLeave={() => setActiveCategory(null)}
                                >
                                    <Link
                                        href={item.path || "#"}
                                        prefetch
                                        className="flex items-center gap-2 text-gray-800 font-medium text-sm uppercase hover:text-red-600"
                                    >
                                        {item.icon && <item.icon size={18} />}
                                        {item.label}
                                        {item.hasMegaMenu && <ChevronDown size={16} className="transition-transform group-hover:rotate-180" />}
                                    </Link>

                                    {item.hasMegaMenu && (
                                        <div className="absolute top-full left-0 z-50 w-[930px] bg-white shadow-2xl border-t-2 border-red-600 rounded-b-lg grid grid-cols-12 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 overflow-hidden">
                                            {/* Column 1: Category Selection */}
                                            <div className="col-span-4 bg-gray-50 py-6 px-4 border-r border-gray-100">
                                                <div className="space-y-1">
                                                    {item.categories.map((cat) => (
                                                        <Link
                                                            key={cat.slug}
                                                            href={`/category/${cat.slug}`}
                                                            prefetch
                                                            className={`flex items-center justify-between py-3 px-4 rounded-md transition-all ${
                                                                (activeCategory === cat.slug || (!activeCategory && item.categories[0].slug === cat.slug))
                                                                    ? "bg-white text-red-600 font-bold shadow-md translate-x-1"
                                                                    : "hover:bg-white text-gray-700 hover:text-red-600"
                                                            }`}
                                                            onMouseEnter={(e) => {
                                                                e.stopPropagation();
                                                                setActiveCategory(cat.slug);
                                                            }}
                                                        >
                                                            <div className="flex items-center gap-3 font-poppins">
                                                                <cat.icon size={18} />
                                                                <span>{cat.title}</span>
                                                            </div>
                                                            <ChevronRight size={14} className={activeCategory === cat.slug ? "opacity-100" : "opacity-0"} />
                                                        </Link>
                                                    ))}
                                                </div>
                                            </div>

                                            {/* Column 2: Subcategories (The Grid) */}
                                            <div className="col-span-8 py-8 px-10 bg-white min-h-[400px]">
                                                {(() => {
                                                    const cur = item.categories.find(c => c.slug === activeCategory) || item.categories[0];
                                                    if (!cur || !cur.items || cur.items.length === 0) {
                                                        return (
                                                            <div className="h-full flex flex-col items-center justify-center text-gray-400">
                                                                <ShoppingBag size={48} className="mb-4 opacity-10" />
                                                                <p>No subcategories found in {cur?.title}</p>
                                                            </div>
                                                        );
                                                    }
                                                    return (
                                                        <div className="grid grid-cols-2 gap-10">
                                                            {cur.items.map((sub, sIdx) => (
                                                                <div key={sIdx} className="space-y-3">
                                                                    <div className="font-bold text-gray-900 uppercase text-xs tracking-wider border-b border-gray-100 pb-2">
                                                                        <Link href={`/subcategory/${sub.slug}`} prefetch className="hover:text-red-600" onClick={() => setActiveCategory(null)}>
                                                                            {sub.name}
                                                                        </Link>
                                                                    </div>
                                                                    <div className="flex flex-col space-y-2">
                                                                        {sub.sub.map((child, cIdx) => (
                                                                            <Link key={cIdx} href={`/childcategory/${child.slug}`} className="text-gray-600 hover:text-red-600 text-sm transition-colors" onClick={() => setActiveCategory(null)}>
                                                                                {child.name}
                                                                            </Link>
                                                                        ))}
                                                                    </div>
                                                                </div>
                                                            ))}
                                                        </div>
                                                    );
                                                })()}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>

                        <div className="text-sm">
                            HOTLINE: <span className="text-red-600 font-bold">{hotline}</span>
                        </div>
                    </div>

                    {/* Mobile Navigation */}
                    <div className="lg:hidden flex items-center justify-between h-14">
                        <div className="font-bold text-xl tracking-tight">MENU</div>
                        <button 
                            onClick={() => setIsMobileOpen(!isMobileOpen)} 
                            className="p-2"
                            aria-label={isMobileOpen ? "Close menu" : "Open menu"}
                        >
                            {isMobileOpen ? <X size={24} /> : <Menu size={24} />}
                        </button>
                    </div>
                </div>

                {/* Mobile Menu */}
                {isMobileOpen && (
                    <div className="lg:hidden bg-white border-t max-h-[80vh] overflow-y-auto pb-10">
                        <div className="px-4 py-3 space-y-1">
                            {navItems.map((item, index) => (
                                <div key={index} className="border-b border-gray-50 last:border-b-0">
                                    {item.hasMegaMenu ? (
                                        <div>
                                            <div className="flex items-center justify-between py-4 px-3 hover:bg-gray-50 rounded-md">
                                                <Link href={item.path} prefetch className="flex items-center gap-3 flex-1" onClick={() => setIsMobileOpen(false)}>
                                                    {item.icon && <item.icon size={20} />}
                                                    <span className="font-medium text-gray-800 uppercase">{item.label}</span>
                                                </Link>
                                                <button onClick={() => toggleMobileMenu(`main-${index}`)} className="p-2">
                                                    <ChevronDown size={20} className={`transition-transform ${openMobileMenus[`main-${index}`] ? "rotate-180" : ""}`} />
                                                </button>
                                            </div>
                                            {openMobileMenus[`main-${index}`] && (
                                                <div className="bg-gray-50 px-2 pb-2">
                                                    {item.categories.map((cat, catIdx) => (
                                                        <div key={catIdx} className="border-t border-gray-100 first:border-0">
                                                            <div className="flex items-center justify-between py-3 px-4">
                                                                <Link href={`/category/${cat.slug}`} className="flex-1 text-gray-700 font-medium" onClick={() => setIsMobileOpen(false)}>
                                                                    {cat.title}
                                                                </Link>
                                                                <button onClick={() => toggleMobileMenu(`cat-${index}-${catIdx}`)} className="p-2">
                                                                    <ChevronRight size={16} className={`transition-transform ${openMobileMenus[`cat-${index}-${catIdx}`] ? "rotate-90" : ""}`} />
                                                                </button>
                                                            </div>
                                                            {openMobileMenus[`cat-${index}-${catIdx}`] && (
                                                                <div className="bg-white pl-8 pr-4 py-2 space-y-4 shadow-inner">
                                                                    {cat.items.map((sub, sIdx) => (
                                                                        <div key={sIdx}>
                                                                            <Link href={`/subcategory/${sub.slug}`} prefetch className="font-bold text-gray-800 text-sm" onClick={() => setIsMobileOpen(false)}>
                                                                                {sub.name}
                                                                            </Link>
                                                                            <div className="flex flex-col mt-1 space-y-1">
                                                                                {sub.sub.map((child, cIdx) => (
                                                                                    <Link key={cIdx} href={`/childcategory/${child.slug}`} className="text-gray-500 text-sm py-1" onClick={() => setIsMobileOpen(false)}>
                                                                                        {child.name}
                                                                                    </Link>
                                                                                ))}
                                                                            </div>
                                                                        </div>
                                                                    ))}
                                                                </div>
                                                            )}
                                                        </div>
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    ) : (
                                        <Link href={item.path} prefetch className="flex items-center gap-3 py-4 px-3 text-gray-800 font-medium hover:bg-gray-50 rounded-md" onClick={() => setIsMobileOpen(false)}>
                                            {item.icon && <item.icon size={20} />}
                                            <span className="uppercase">{item.label}</span>
                                        </Link>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>
                )}
            </nav>
        </header>
    );
};

export default Header;
