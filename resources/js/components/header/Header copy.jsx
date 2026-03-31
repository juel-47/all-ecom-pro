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
  Gem,
  Calendar,
} from "lucide-react";
import TopHeader from "./TopHeader"; // assuming you have this component
import { Link, usePage } from "@inertiajs/react";

const Header = () => {
    const { categoriess } = usePage().props;
    const [isMobileOpen, setIsMobileOpen] = useState(false);
    const [activeCategory, setActiveCategory] = useState(null);
    const [openMobileMenus, setOpenMobileMenus] = useState({});

    // Map database categories to the format expected by the Nav/MegaMenu
    const dynamicCategories = (categoriess || []).map(cat => ({
        title: cat.name,
        slug: cat.slug,
        icon: Gift, // Default icon for categories
        items: cat.sub_categories?.map(sub => ({
            name: sub.name,
            slug: sub.slug,
            sub: sub.child_categories?.map(child => ({
                name: child.name,
                slug: child.slug
            }))
        })) || []
    }));

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

    const hotline = "+45 55 37 13 518";

    const toggleMobileMenu = (key) => {
        setOpenMobileMenus((prev) => ({
            ...prev,
            [key]: !prev[key],
        }));
    };

    return (
        <header className="sticky top-0 z-50 bg-white shadow-sm border-b border-b-black/50">
            <TopHeader />

            <nav className="bg-white">
                <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                    {/* DESKTOP VERSION */}
                    <div className="hidden lg:flex items-center justify-between h-12">
                        <div className="flex items-center space-x-10">
                            {navItems.map((item, index) => (
                                <div
                                    key={index}
                                    className="relative group"
                                    onMouseEnter={() => {
                                        if (item.hasMegaMenu) {
                                            setActiveCategory(item.categories[0]?.title || null);
                                        }
                                    }}
                                    onMouseLeave={() => setActiveCategory(null)}
                                >
                                    <Link
                                        href={item.path || "#"}
                                        prefetch
                                        className={`
                                            flex items-center cursor-pointer gap-2 py-4 text-gray-800 font-medium text-sm uppercase tracking-wide
                                            ${item.hasMegaMenu ? "cursor-default" : "hover:text-red-600"}
                                        `}
                                    >
                                        {item.icon && <item.icon size={18} />}
                                        {item.label}
                                        {item.hasMegaMenu && (
                                            <ChevronDown
                                                size={16}
                                                className="transition-transform group-hover:rotate-180"
                                            />
                                        )}
                                    </Link>

                                    {/* Mega Menu */}
                                    {item.hasMegaMenu && activeCategory && (
                                        <div
                                            className="
                                                absolute top-full left-0 z-50 
                                                w-225 bg-white shadow-2xl border-t-2 border-red rounded-b-lg
                                                grid grid-cols-12 gap-0 overflow-hidden
                                            "
                                        >
                                            <div className="col-span-4 bg-gray-50 py-6 px-4">
                                                {item.categories.map((cat) => (
                                                        <Link 
                                                            key={cat.title}
                                                            href={`/category/${cat.slug}`}
                                                            prefetch
                                                            className={`
                                                                flex items-center gap-3 py-3 px-4 cursor-pointer rounded-md transition-colors
                                                                ${activeCategory === cat.title
                                                                    ? "bg-white text-red-600 font-medium shadow-sm"
                                                                    : "hover:bg-white text-gray-700"
                                                                }
                                                            `}
                                                            onMouseEnter={() => setActiveCategory(cat.title)}
                                                        >
                                                            {cat.icon && <cat.icon size={18} />}
                                                            {cat.title}
                                                        </Link>
                                                ))}
                                            </div>

                                            {/* Right - Subcategories & Children */}
                                            <div className="col-span-8 py-6 px-8">
                                                {item.categories.find((c) => c.title === activeCategory)
                                                    ?.items && (
                                                    <div className="grid grid-cols-2 gap-x-12 gap-y-6">
                                                        {item.categories
                                                            .find((c) => c.title === activeCategory)
                                                            ?.items?.map((subItem, idx) => (
                                                                <div
                                                                    key={idx}
                                                                    className="min-w-0 border-b border-gray-200 pb-0"
                                                                >
                                                                    <div className="font-semibold text-gray-900 mb-2 border-b border-gray-200 pb-1">
                                                                        <Link 
                                                                            href={`/subcategory/${subItem.slug}`}
                                                                            prefetch
                                                                            onClick={() => setActiveCategory(null)}
                                                                        >
                                                                            {subItem.name}
                                                                        </Link>
                                                                    </div>
                                                                    {subItem.sub?.map((child, childIdx) => (
                                                                        <Link
                                                                            key={childIdx}
                                                                            href={`/childcategory/${child.slug}`}
                                                                            prefetch
                                                                            className="block py-1.5 text-gray-600 hover:text-red-600 text-sm pl-1"
                                                                            onClick={() => setActiveCategory(null)}
                                                                        >
                                                                            {child.name}
                                                                        </Link>
                                                                    ))}
                                                                </div>
                                                            ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>

                        <div className="text-sm font-medium">
                            HOTLINE:{" "}
                            <span className="text-red-600 font-semibold">{hotline}</span>
                        </div>
                    </div>

                    {/* MOBILE VERSION */}
                    <div className="lg:hidden flex items-center justify-between h-14">
                        <div className="font-bold text-xl tracking-tight">MENUS</div>
                        <button
                            onClick={() => setIsMobileOpen(!isMobileOpen)}
                            className="p-2 rounded-md hover:bg-gray-100"
                        >
                            {isMobileOpen ? <X size={24} /> : <Menu size={24} />}
                        </button>
                    </div>
                </div>

                {/* Mobile Mega Menu / Accordion */}
                {isMobileOpen && (
                    <div className="lg:hidden bg-white border-t max-h-[75vh] overflow-y-auto">
                        <div className="px-4 py-3 space-y-1">
                            {navItems.map((item, index) => (
                                <div
                                    key={index}
                                    className="border-b border-gray-100 last:border-b-0"
                                >
                                    {item.hasMegaMenu ? (
                                        <div className="relative">
                                            <div className="w-full flex items-center justify-between py-4 px-3 text-gray-800 font-medium hover:bg-gray-50 rounded-md">
                                                <Link
                                                    href={item.path}
                                                    className="flex items-center gap-3 flex-1"
                                                    onClick={() => setIsMobileOpen(false)}
                                                >
                                                    {item.icon && <item.icon size={20} />}
                                                    <span>{item.label}</span>
                                                </Link>

                                                <button
                                                    type="button"
                                                    onClick={(e) => {
                                                        e.stopPropagation();
                                                        toggleMobileMenu(`main-${index}`);
                                                    }}
                                                    className="p-2 -mr-2"
                                                >
                                                    <ChevronDown
                                                        size={20}
                                                        className={`transition-transform text-gray-600 ${openMobileMenus[`main-${index}`]
                                                            ? "rotate-180"
                                                            : ""
                                                            }`}
                                                    />
                                                </button>
                                            </div>

                                            {openMobileMenus[`main-${index}`] && (
                                                <div className="bg-gray-50 rounded-b-md mb-2 overflow-hidden">
                                                    {item.categories.map((cat, catIndex) => (
                                                        <div
                                                            key={cat.title}
                                                            className="border-t border-gray-200 first:border-t-0"
                                                        >
                                                            <button
                                                                onClick={() =>
                                                                    toggleMobileMenu(`cat-${index}-${catIndex}`)
                                                                }
                                                                className="w-full flex items-center justify-between py-3 px-6 text-gray-700 hover:bg-gray-100"
                                                            >
                                                                <div className="flex items-center gap-3">
                                                                    {cat.icon && <cat.icon size={18} />}
                                                                    <span className="font-medium">
                                                                        {cat.title}
                                                                    </span>
                                                                </div>
                                                                <ChevronRight
                                                                    size={16}
                                                                    className={`transition-transform ${openMobileMenus[`cat-${index}-${catIndex}`]
                                                                        ? "rotate-90"
                                                                        : ""
                                                                        }`}
                                                                />
                                                            </button>

                                                            {openMobileMenus[`cat-${index}-${catIndex}`] && (
                                                                <div className="bg-white py-2 px-10 border-t border-gray-100">
                                                                    {cat.items.map((subItem, subIdx) => (
                                                                        <div key={subIdx}>
                                                                            <div className="font-medium text-gray-800 py-2">
                                                                                <Link
                                                                                    href={`/subcategory/${subItem.slug}`}
                                                                                    onClick={() => setIsMobileOpen(false)}
                                                                                >
                                                                                    {subItem.name}
                                                                                </Link>
                                                                            </div>
                                                                            {subItem.sub?.map(
                                                                                (child, childIdx) => (
                                                                                    <Link
                                                                                        key={childIdx}
                                                                                        href={`/childcategory/${child.slug}`}
                                                                                        className="block py-1.5 pl-4 text-gray-600 hover:text-red-600 text-sm"
                                                                                        onClick={() =>
                                                                                            setIsMobileOpen(false)
                                                                                        }
                                                                                    >
                                                                                        {child.name}
                                                                                    </Link>
                                                                                )
                                                                            )}
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
                                        <Link
                                            href={item.path || "#"}
                                            className="flex items-center gap-3 py-4 px-3 text-gray-800 font-medium hover:bg-gray-50 rounded-md"
                                            onClick={() => setIsMobileOpen(false)}
                                        >
                                            {item.icon && <item.icon size={20} />}
                                            {item.label}
                                        </Link>
                                    )}
                                </div>
                            ))}

                            <div className="mt-6 pt-4 border-t border-gray-200 text-center py-3">
                                <p className="text-sm text-gray-600">
                                    HOTLINE:{" "}
                                    <span className="font-semibold text-red-600">{hotline}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                )}
            </nav>
        </header>
    );
};

export default Header;
