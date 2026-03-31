import { Link } from "@inertiajs/react";
import ProductCard from "../ProductCard";
import { ProductCardSkeleton } from "../Skeleton";
export default function LatestProducts({ products }) {
  // Filter categories that actually have products
  const activeCategories = (products || []).filter(
    (category) => category.products && category.products.length > 0
  );

  if (!products || products.length === 0 || activeCategories.length === 0) {
    return (
      <section className="py-10 md:py-16 bg-gray-50">
        <div className="container px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center mb-8">
            <div className="h-10 w-64 bg-gray-200 animate-pulse rounded"></div>
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
            {[...Array(6)].map((_, i) => (
              <ProductCardSkeleton key={i} />
            ))}
          </div>
        </div>
      </section>
    );
  }

  return (
    <>
      {activeCategories.map((category, index) => (
        <section
          key={index}
          className={`py-10 md:py-16 border-t border-gray-200 first:border-t-0 ${
            index % 2 === 0 ? "bg-gray-50" : "bg-gray-100"
          }`}
        >
          <div className="container px-4 sm:px-6 lg:px-8">
            {/* Header */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
              <h2 className="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 uppercase">
                {category.name}
              </h2>

              <Link href={`/category/${category.slug}`} className="bg-red text-white px-6 py-2.5 rounded-full text-sm md:text-base font-medium hover:bg-red-800 transition-colors duration-300 whitespace-nowrap">
                View All
              </Link>
            </div>

            {/* Products Grid */}
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
              {category.products.map((product, pIndex) => (
                <ProductCard key={pIndex} product={product} />
              ))}
            </div>
          </div>
        </section>
      ))}
    </>
  );
}
