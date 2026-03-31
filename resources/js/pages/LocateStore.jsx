 
import React from "react";
import { MapPin,  ArrowRight, Mail, Phone } from "lucide-react";

import Breadcrumb from "../components/Breadcrumb";

const LocateStore = ({ branches }) => { 
  const shops = branches || [];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Section */}
      <div className="relative h-[40vh] min-h-125 overflow-hidden">
        <img
          src="https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=1600&q=80"
          alt="Copenhagen colorful buildings"
          className="absolute inset-0 w-full h-full object-cover brightness-75"
        />
        <div className="absolute inset-0 bg-linear-to-b from-transparent via-black/30 to-black/60" />

        <div className="relative h-full container mx-auto px-4 flex flex-col justify-center items-center text-center text-white">
          <div className="max-w-4xl">
            <h1 className="text-4xl   lg:text-6xl font-bold mb-6 leading-tight">
              Visit one of our shops in Copenhagen
            </h1>
            <p className="text-xl md:text-2xl font-light italic mb-10">
              Find the shop closest to you
            </p>

            <a
              href="#shops"
              className="inline-flex items-center gap-3 bg-red text-white px-8 py-4 rounded-lg font-medium text-lg hover:bg-red-800 transition shadow-lg"
            >
              Find the shops
              <ArrowRight size={20} />
            </a>
          </div>
        </div>
      </div>

      {/* Shops List */}
      <div id="shops" className="container   px-4 py-16 md:py-20">
        <div className=" ">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {shops.length > 0 ? (
              shops.map((shop, index) => (
                <div
                  key={index}
                  className="grid sm:grid-cols-2 gap-8 lg:gap-12 items-center bg-white rounded-xl shadow-sm border border-gray overflow-hidden hover:shadow-md transition-shadow"
                >
                  {/* Image */}
                  <div className="w-full h-auto ">
                    <img
                      src={shop.image ? `/${shop.image}` : "/products/3.png"}
                      alt={shop.name}
                      className="w-full sm:w-75 h-full object-cover"
                    />
                  </div>

                  {/* Info */}
                  <div className="p-8 lg:p-2 pl-0! lg:py-4">
                    <h2 className="text-2xl md:text-xl font-bold text-gray-900 mb-4">
                      {shop.name}
                    </h2>

                    <div className="space-y-3">
                      <div className="flex items-start gap-3">
                        <MapPin size={20} className="text-red mt-1 shrink-0" />
                        <div>
                          <p className="font-medium text-gray-800 text-sm">
                            {shop.address}
                          </p>
                          <p className="text-gray-600 mt-1 text-sm">
                            København, Denmark
                          </p>
                        </div>
                      </div>

                      <div className="flex items-start gap-3">
                        <Mail size={20} className="text-red mt-1 shrink-0" />

                        <div>
                          <p className="font-medium text-gray-800 text-sm">
                            Email
                          </p>
                          <p className="text-gray-600  text-sm">{shop.email}</p>
                        </div>
                      </div>

                      <div className="flex items-start gap-3">
   
                        <Phone size={20} className="text-red mt-1 shrink-0" />
                        <div>
                          <p className="font-medium text-gray-800 text-sm">
                            Phone
                          </p>
                          <p className="text-gray-600 text-sm">{shop.phone}</p>
                        </div>
                      </div>

                      <div className="pt-2">
                        <a
                          href={shop.location_url}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="inline-flex items-center gap-2 text-red hover:text-red-800 font-medium transition"
                        >
                          Find shop on map
                          <ArrowRight size={18} />
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              ))
            ) : (
                <div className="col-span-full py-20 text-center">
                    <div className="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <MapPin size={40} className="text-gray-300" />
                    </div>
                    <h3 className="text-2xl font-bold text-gray-400">No store locations found</h3>
                    <p className="text-gray-500 mt-2">Check back soon for new locations.</p>
                </div>
            )}
          </div>

      
        </div>
      </div>
    </div>
  );
};

export default LocateStore;
