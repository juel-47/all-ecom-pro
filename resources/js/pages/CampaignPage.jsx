// CampaignPage.jsx
import React, { useState, useEffect } from "react";
import { Link } from "@inertiajs/react";
import ProductCard from "../components/ProductCard";

const CampaignPage = ({ campaigns, featuredCampaign, featuredProducts }) => {
  const [timeLeft, setTimeLeft] = useState({
    days: 0,
    hours: 0,
    minutes: 0,
    seconds: 0,
  });


  // Real countdown timer effect based on featuredCampaign?.end_date
  useEffect(() => {
    if (!featuredCampaign?.end_date) return;

    const calculateTime = () => {
      const difference = +new Date(featuredCampaign.end_date) - +new Date();
      let timeLeftValues = { days: 0, hours: 0, minutes: 0, seconds: 0 };

      if (difference > 0) {
        timeLeftValues = {
          days: Math.floor(difference / (1000 * 60 * 60 * 24)),
          hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
          minutes: Math.floor((difference / 1000 / 60) % 60),
          seconds: Math.floor((difference / 1000) % 60),
        };
      }
      return timeLeftValues;
    };

    const timer = setInterval(() => {
      setTimeLeft(calculateTime());
    }, 1000);

    // Initial calculation
    setTimeLeft(calculateTime());

    return () => clearInterval(timer);
  }, [featuredCampaign]);

  // Handle case where no campaign exists
  if (!featuredCampaign) {
      return (
          <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
              <div className="text-center">
                  <h1 className="text-2xl font-bold text-gray-900 mb-2">No Active Campaigns</h1>
                  <p className="text-gray-600">Check back soon for amazing deals!</p>
              </div>
          </div>
      );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Banner (User's Design) */}
      <div className="bg-linear-to-br from-red via-red-700 to-red-900 text-white">
        <div className="container mx-auto px-4 py-16 md:py-24 lg:py-32">
          <div className="max-w-4xl mx-auto text-center">
            <div className="inline-block bg-white/20 backdrop-blur-sm px-6 py-2 rounded-full mb-6 text-lg font-medium">
              LIMITED TIME OFFER • ENDS IN {timeLeft.days}d {timeLeft.hours}h{" "}
              {timeLeft.minutes}m
            </div>

            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6  ">
              {featuredCampaign.title || featuredCampaign.name}
            </h1>

            <p className="text-xl md:text-2xl mb-10 opacity-90 max-w-3xl mx-auto">
              {featuredCampaign.sub_title || 'Exclusive discounts on authentic Danish items'}
            </p>

            <div className="flex flex-wrap justify-center gap-4 md:gap-6 mb-12">
              <div className="bg-white/25 backdrop-blur-sm px-8 py-4 rounded-xl text-center min-w-25">
                <div className="text-3xl font-bold">{timeLeft.days}</div>
                <div className="text-sm uppercase tracking-wider">Days</div>
              </div>
              <div className="bg-white/25 backdrop-blur-sm px-8 py-4 rounded-xl text-center min-w-25">
                <div className="text-3xl font-bold">
                  {timeLeft.hours.toString().padStart(2, "0")}
                </div>
                <div className="text-sm uppercase tracking-wider">Hours</div>
              </div>
              <div className="bg-white/25 backdrop-blur-sm px-8 py-4 rounded-xl text-center min-w-25">
                <div className="text-3xl font-bold">
                  {timeLeft.minutes.toString().padStart(2, "0")}
                </div>
                <div className="text-sm uppercase tracking-wider">Minutes</div>
              </div>
            </div>

            <a
              href="#products"
              className="inline-block bg-white text-red font-bold text-lg px-10 py-5 rounded-full hover:bg-gray-100 transform hover:scale-105 transition-all shadow-xl"
            >
              Shop the Sale Now →
            </a>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="container mx-auto px-4 py-16">
        {/* Trust Signals */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16 text-center">
          <div>
            <div className="text-4xl mb-3">🚚</div>
            <p className="font-bold">Free Shipping</p>
            <p className="text-sm text-gray-600">on orders over 499 DKK</p>
          </div>
          <div>
            <div className="text-4xl mb-3">↩️</div>
            <p className="font-bold">30 Days Return</p>
            <p className="text-sm text-gray-600">Hassle-free</p>
          </div>
          <div>
            <div className="text-4xl mb-3">🔒</div>
            <p className="font-bold">Secure Payment</p>
            <p className="text-sm text-gray-600">Protected checkout</p>
          </div>
          <div>
            <div className="text-4xl mb-3">⭐</div>
            <p className="font-bold">4.8+ Rating</p>
            <p className="text-sm text-gray-600">From 5000+ customers</p>
          </div>
        </div>

        {/* Products Section */}
        <div id="products" className="mb-16">
          <h2 className="text-3xl md:text-4xl font-bold text-center mb-12">
            Campaign Highlights
          </h2>

          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-6">
            {featuredProducts.map((cp) => (
                <ProductCard key={cp.id} product={cp.product} />
            ))}
          </div>
        </div>

        {/* Other Active Campaigns (if any) */}
        {campaigns.filter(c => c.id !== featuredCampaign.id).length > 0 && (
            <div className="mb-20">
                <h2 className="text-2xl md:text-3xl font-bold text-center mb-10">
                    More Active Campaigns
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {campaigns.filter(c => c.id !== featuredCampaign.id).map(c => (
                        <a 
                            key={c.id} 
                            href={`/campaign/${c.slug}`}
                            className="group relative h-64 rounded-2xl overflow-hidden shadow-lg transform hover:-translate-y-2 transition-all duration-300"
                        >
                            <img 
                                src={c.banner ? `/storage/${c.banner}` : '/placeholder-banner.jpg'} 
                                alt={c.name}
                                className="w-full h-full object-cover group-hover:scale-110 transition-duration-700"
                            />
                            <div className="absolute inset-0 bg-linear-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-6">
                                <h3 className="text-xl font-bold text-white mb-2">{c.title || c.name}</h3>
                                <p className="text-white/80 text-sm line-clamp-2 mb-4">{c.sub_title}</p>
                                <span className="inline-block w-fit bg-red text-white text-xs font-bold px-4 py-2 rounded-full">
                                    View Products →
                                </span>
                            </div>
                        </a>
                    ))}
                </div>
            </div>
        )}

        {/* Final CTA */}
        <div className="bg-linear-to-r from-red-50 to-red-100 rounded-3xl p-10 md:p-16 text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            Don't Miss Out!
          </h2>
          <p className="text-xl text-gray-700 mb-10 max-w-3xl mx-auto">
            All campaign prices end when the timer reaches zero. Stock is
            limited — shop now!
          </p>

          <Link
            href="/campaign-products"
            className="inline-block bg-red text-white font-bold text-lg px-12 py-6 rounded-full hover:bg-red-800 transform hover:scale-105 transition-all shadow-lg"
          >
            Shop All Campaign Products →
          </Link>

          <p className="mt-8 text-sm text-gray-600">
            Offer valid until {new Date(featuredCampaign.end_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })} or while stocks last
          </p>
        </div>
      </div>
    </div>
  );
};

export default CampaignPage;
