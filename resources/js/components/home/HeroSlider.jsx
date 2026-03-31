import React, { useState } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import { Link } from "@inertiajs/react";
import Skeleton from "../Skeleton";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/autoplay";


const HeroSlider = ({sliders}) => {
  const [loadedImages, setLoadedImages] = useState({});

  const handleImageLoad = (index) => {
    setLoadedImages((prev) => ({ ...prev, [index]: true }));
  };

  if (!sliders || sliders.length === 0) {
    return (
      <div className="w-full h-[300px] sm:h-[400px] lg:h-[500px]">
        <Skeleton className="w-full h-full" />
      </div>
    );
  }

  return (
    <div className="relative w-full overflow-hidden min-h-[300px] sm:min-h-[400px] lg:min-h-[500px]">
      {/* হাইট রেসপন্সিভ করা হয়েছে */}
      <Swiper
        modules={[Navigation, Pagination, Autoplay]}
        spaceBetween={0}
        slidesPerView={1}
        loop={true}
        autoplay={{ delay: 5000, disableOnInteraction: false }}
        navigation={{
          prevEl: ".custom-swiper-button-prev",
          nextEl: ".custom-swiper-button-next",
        }}
        pagination={{
          el: ".custom-swiper-pagination",
          clickable: true,
          renderBullet: (index, className) =>
            `<span class="${className} custom-dot"></span>`,
        }}
        className="w-full"
      >
        {sliders.map((slide, index) => {
          const isFirst = index === 0;
          const imageUrl = slide.banner.startsWith('http') 
            ? slide.banner 
            : (slide.banner.startsWith('storage/') ? `/${slide.banner}` : `/storage/${slide.banner}`);
          
          return (
            <SwiperSlide key={index} className="h-full">
              <Link 
                href={slide.btn_url || "#"} 
                target="_blank" 
                rel="noopener noreferrer" 
                className="block w-full h-full"
              >
                <div className="w-full h-[300px] sm:h-[400px] lg:h-[500px] relative">
                  {!loadedImages[index] && (
                    <Skeleton className="absolute inset-0 w-full h-full z-10" />
                  )}
                  <img
                    src={imageUrl}
                    alt={slide.title || "Hero Banner"}
                    className={`w-full h-full object-cover transition-opacity duration-500 ${loadedImages[index] ? 'opacity-100' : 'opacity-0'}`}
                    fetchpriority={isFirst ? "high" : "auto"}
                    loading={isFirst ? "eager" : "lazy"}
                    decoding={isFirst ? "sync" : "async"}
                    onLoad={() => handleImageLoad(index)}
                  />
                  <div className="absolute inset-0 bg-black/15" />
                </div>
              </Link>
            </SwiperSlide>
          );
        })}
      </Swiper>

      {/* Navigation – মোবাইলে ছোট/অপশনাল */}
      <button
        className="
          custom-swiper-button-prev 
          absolute left-3 sm:left-6 lg:left-12 top-1/2 -translate-y-1/2 z-20
          w-10 h-10 sm:w-12 sm:h-12 lg:w-10 lg:h-10 
          flex items-center justify-center rounded-full 
          bg-white/25 backdrop-blur-sm text-white 
          hover:bg-white/40 transition-all border border-white/40
        "
      >
        <svg
          className="w-6 h-6 sm:w-7 sm:h-7"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M15 19l-7-7 7-7"
          />
        </svg>
      </button>

      <button
        className="
          custom-swiper-button-next 
          absolute right-3 sm:right-6 lg:right-12 top-1/2 -translate-y-1/2 z-20
          w-10 h-10 sm:w-12 sm:h-12 lg:w-10 lg:h-10 
          flex items-center justify-center rounded-full 
          bg-white/25 backdrop-blur-sm text-white 
          hover:bg-white/40 transition-all border border-white/40
        "
      >
        <svg
          className="w-6 h-6 sm:w-7 sm:h-7"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M9 5l7 7-7 7"
          />
        </svg>
      </button>

      {/* Pagination – মোবাইলে নিচে ভালো করে দেখানো */}
      <div className="custom-swiper-pagination absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-3 sm:gap-4" />
    </div>
  );
};

export default HeroSlider;
