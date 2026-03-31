// CustomerStories.jsx
import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";

const testimonials = [
  {
    rating: 5,
    date: "12 December 2025",
    text: "It's really too good And delivery timings are best",
    author: "Anupriya Rathore A",
    productImg: "/categories/1.svg", // small product image
  },
  {
    rating: 5,
    date: "12 December 2025",
    text: "It's really too good And delivery timings are best",
    author: "Anupriya Rathore A",
    productImg: "/categories/2.svg",
  },
  {
    rating: 5,
    date: "12 December 2025",
    text: "It's really too good And delivery timings are best",
    author: "Anupriya Rathore A",
    productImg: "/categories/3.svg",
  },
  {
    rating: 5,
    date: "12 December 2025",
    text: "It's really too good And delivery timings are best",
    author: "Anupriya Rathore A",
    productImg: "/categories/4.svg",
  },
  {
    rating: 5,
    date: "12 December 2025",
    text: "It's really too good And delivery timings are best",
    author: "Anupriya Rathore A",
    productImg: "/categories/5.svg",
  },
  // You can add more real testimonials here...
];

export default function CustomerStories() {
  return (
    <section className="py-12 md:py-16 bg-white">
      <div className="container px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-10 gap-4">
          <div>
            <h2 className="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900">
              CUSTOMERS STORIES
            </h2> 
          </div>

          <button className="bg-red text-white px-6 py-2.5 rounded-full text-sm md:text-base font-medium hover:bg-red-800 transition-colors duration-300 whitespace-nowrap">
            View All
          </button>
        </div>

        {/* Slider */}
        <div className="relative">
          <Swiper
            modules={[Navigation]}
            spaceBetween={16}
            slidesPerView={1}
            navigation={{
              nextEl: ".swiper-button-next-custom",
              prevEl: ".swiper-button-prev-custom",
            }}
            breakpoints={{
              480: {
                slidesPerView: 1.2,
                spaceBetween: 20,
              },
              640: {
                slidesPerView: 2,
                spaceBetween: 24,
              },
              768: {
                slidesPerView: 2.5,
                spaceBetween: 28,
              },
              1024: {
                slidesPerView: 3,
                spaceBetween: 32,
              },
              1280: {
                slidesPerView: 3,
                spaceBetween: 36,
              },
            }}
          >
            {testimonials.map((testimonial, index) => (
              <SwiperSlide key={index}>
                <div className="bg-white border border-[var(--color-gray)] rounded-xl p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow duration-300 h-full flex flex-col">
                  {/* Small product image */}
                  <div className="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4">
                    <img
                      src={testimonial.productImg}
                      alt="Product"
                      className="w-full h-full object-contain"
                    />
                  </div>

                  {/* Stars */}
                  <div className="flex justify-center gap-0.5 mb-3">
                    {[...Array(5)].map((_, i) => (
                      <svg
                        key={i}
                        className="w-5 h-5 md:w-6 md:h-6 text-yellow-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>

                  {/* Date */}
                  <p className="text-center text-xs md:text-sm text-gray-500 mb-4">
                    {testimonial.date}
                  </p>

                  {/* Quote & Text */}
                  <div className="flex-1 flex flex-col justify-between">
                    <div className="text-center">
                      <span className="text-[var(--color-red)] text-4xl md:text-5xl leading-none">
                        “
                      </span>
                      <p className="mt-1 md:mt-2 text-gray-700 text-sm md:text-base leading-relaxed">
                        {testimonial.text}
                      </p>
                    </div>

                    <div className="mt-4 text-center">
                      <p className="text-sm md:text-base font-medium text-gray-800">
                        - {testimonial.author}
                      </p>
                    </div>
                  </div>
                </div>
              </SwiperSlide>
            ))}
          </Swiper>

          {/* Custom Navigation Arrows */}
          <button className="swiper-button-prev-custom absolute left-0 md:-left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-12 md:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:text-[var(--color-red)] transition-colors border border-[var(--color-gray)]">
            <span className="text-2xl md:text-3xl">←</span>
          </button>

          <button className="swiper-button-next-custom absolute right-0 md:-right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-12 md:h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 hover:text-[var(--color-red)] transition-colors border border-[var(--color-gray)]">
            <span className="text-2xl md:text-3xl">→</span>
          </button>
        </div>
      </div>
    </section>
  );
}
