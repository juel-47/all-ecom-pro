// pages/Support.jsx
import React from "react";
import { FaEnvelope, FaPhoneAlt, FaClock, FaRegHeart } from "react-icons/fa";
import { usePage } from "@inertiajs/react";

const SupportPage = () => {
  const {props}=usePage();
  const {footerInfo}=props;
  const supportEmail=footerInfo?.email || "";
  const supportPhone=footerInfo?.phone || "";
  return (
    <div className="min-h-screen bg-dark1 py-20 ">
      <div className="container px-6 2xl:px-20">
        <div className="">
          {/* Header */}
          <div className="text-center mb-16">
            <h1 className="text-xl lg:text-4xl font-bold text-cream mb-4">
              Help & Support
            </h1>
            <p className="text-sm lg:text-lg text-black">
              We’re here whenever you need us.
            </p>
          </div>

          {/* Main Card */}
          <div className="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div className="bg-dark2 py-12 text-center">
              <FaRegHeart className="text-6xl text-cream mx-auto mb-4" />
              <p className="text-cream text-2xl font-medium">
                You’re never alone with Hygge Cotton
              </p>
            </div>

            <div className="p-12 md:p-16 text-center">
              <p className="text-lg text-cream leading-relaxed mb-12">
                Our support team is available <strong>Monday – Friday</strong>
                <br />
                <span className="text-xl font-semibold">
                  9:00 – 18:00 (CET)
                </span>
              </p>

              {/* Contact Grid */}
              <div className="grid md:grid-cols-3 gap-10 max-w-4xl mx-auto">
                <div className="group">
                  <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 group-hover:bg-dark2 transition">
                    <FaEnvelope className="text-3xl text-red group-hover:text-cream" />
                  </div>
                  <p className="text-black/50 mb-2">Email</p>
                  <a
                    href={`mailto:${supportEmail}`}
                    className="text-xl font-bold text-black/70 hover:underline"
                  >
                    {supportEmail}
                  </a>
                </div>

                <div className="group">
                  <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 group-hover:bg-dark2 transition">
                    <FaPhoneAlt className="text-3xl text-red group-hover:text-cream" />
                  </div>
                  <p className="text-black/50 mb-2">Phone</p>
                  <a
                    href={`tel:${supportPhone}`}
                    className="text-xl font-bold text-black/70 hover:underline"
                  >
                    {supportPhone}
                  </a>
                </div>

                <div className="group">
                  <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 group-hover:bg-dark2 transition">
                    <FaClock className="text-3xl text-red group-hover:bg-dark2" />
                  </div>
                  <p className="text-black/50 mb-2">Response Time</p>
                  <p className="text-xl font-bold text-cream">
                    Within 24 hours
                    <br />
                    <span className="text-sm font-normal text-black/50">
                      (usually much faster)
                    </span>
                  </p>
                </div>
              </div>

              <div className="mt-16 pt-10 border-t border-gray-200">
                <p className="text-2xl text-black/50 font-medium italic">
                  Reach out anytime — we answer with care.
                </p>
              </div>
            </div>
          </div>

          {/* Footer Note */}
          <p className="text-center text-gray mt-12 text-sm">
            Made with love from Copenhagen ♡
          </p>
        </div>
      </div>
    </div>
  );
};

export default SupportPage;
