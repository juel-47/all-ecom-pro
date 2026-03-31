// NotFoundPage.jsx
import React from "react";
import { Home, ArrowLeft, Search } from "lucide-react";
import { Link } from "@inertiajs/react";

const NotFoundPage = () => {
  return (
    <div className="min-h-[85vh] bg-gray-50 flex flex-col">
      {/* Main Content */}
      <main className="grow flex items-center justify-center px-4 py-16 sm:py-24">
        <div className="text-center max-w-3xl">
          {/* Big 404 */}
          <h1 className="text-8xl sm:text-9xl md:text-[12rem] lg:text-[14rem] font-black text-red leading-none tracking-tight opacity-90">
            404
          </h1>

          {/* Subtitle */}
          <h2 className="mt-6 text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900">
            Page not found
          </h2>

          <p className="mt-5 text-lg sm:text-xl text-gray-600 max-w-xl mx-auto">
            Sorry, we couldn't find the page you're looking for. It might have
            been moved, renamed, or doesn't exist.
          </p>
 
          {/* Action buttons */}
          <div className="mt-12 flex flex-col sm:flex-row items-center justify-center gap-5">
            <Link
              href="/"
              className="inline-flex items-center gap-2 px-8 py-4 bg-white border-2 border-red text-red font-medium rounded-full hover:bg-red hover:text-white transition group"
            >
              <Home
                size={20}
                className="group-hover:scale-110 transition-transform"
              />
              Back to Home
            </Link>

            <Link
              href="/all-products"
              className="inline-flex items-center gap-2 px-8 py-4 bg-gray-800 text-white font-medium rounded-full hover:bg-gray-900 transition"
            >
              <ArrowLeft size={20} />
              Browse Products
            </Link>
          </div>

          {/* Fun / friendly message */}
          <p className="mt-16 text-gray-500 text-sm sm:text-base">
            Lost in Copenhagen? Don't worry — our Danish souvenirs are still
            waiting for you! 🛍️🇩🇰
          </p>
        </div>
      </main>
 
    </div>
  );
};

export default NotFoundPage;
