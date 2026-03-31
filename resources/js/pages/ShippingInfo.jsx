// pages/Shipping.jsx  অথবা components/ShippingInfo.jsx
import React from "react";
import {
    FaTruck,
    FaBoxOpen,
    FaClock,
    FaGlobeEurope,
    FaHeart,
} from "react-icons/fa";

const ShippingInfo = () => {
    return (
        <div className="min-h-screen bg-dark1 py-20 ">
            <div className="container px-4 2xl:px-20">
                <div className="max-w-4xl mx-auto">
                    {/* Header */}
                    <div className="text-center mb-16">
                        <h1 className="text-xl lg:text-3xl font-bold text-black mb-4">
                            Shipping Information
                        </h1>
                        <p className="text-sm lg:text-xl text-black/75">
                            From Copenhagen with love ♡
                        </p>
                    </div>

                    {/* Main Card */}
                    <div className="bg-white rounded-3xl shadow-2xl overflow-hidden">
                        <div className="bg-gray-300 py-10 text-center">
                            <FaTruck className="text-6xl text-cream mx-auto mb-4" />
                            <p className="text-cream text-2xl font-medium">
                                Every order is packed with care
                            </p>
                        </div>

                        <div className="p-12 md:p-16">
                            <div className="grid md:grid-cols-2 gap-10 max-w-3xl mx-auto">
                                <div className="flex gap-6">
                                    <div className="w-16 h-16 bg-red-100 rounded-full shrink-0 flex items-center justify-center">
                                        <FaGlobeEurope className="text-3xl text-red" />
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold text-black mb-2">
                                            We Ship From
                                        </h3>
                                        <p className="text-black/50 leading-relaxed">
                                            Denmark — straight from Copenhagen
                                            to
                                            <strong>most EU countries</strong>.
                                        </p>
                                    </div>
                                </div>

                                <div className="flex gap-6">
                                    <div className="w-16 h-16 bg-red-100 rounded-full shrink-0 flex items-center justify-center">
                                        <FaClock className="text-3xl text-red" />
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold text-black mb-2">
                                            Processing Time
                                        </h3>
                                        <p className="text-black/50 leading-relaxed">
                                            Orders are processed within{" "}
                                            <strong>3–7 business days</strong>.
                                            <br />
                                            <span className="text-sm text-black/50">
                                                (Custom orders may take a little
                                                longer — worth the wait!)
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div className="flex gap-6">
                                    <div className="w-16 h-16 bg-red-100 rounded-full shrink-0 flex items-center justify-center">
                                        <FaTruck className="text-3xl text-red" />
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold text-black mb-2">
                                            Delivery Time
                                        </h3>
                                        <p className="text-black/50 leading-relaxed">
                                            Depends on your location —{" "}
                                            <strong>
                                                clearly shown at checkout
                                            </strong>{" "}
                                            so there are no surprises.
                                        </p>
                                    </div>
                                </div>

                                <div className="flex gap-6">
                                    <div className="w-16 h-16 bg-red-100 rounded-full shrink-0 flex items-center justify-center">
                                        <FaBoxOpen className="text-3xl text-red" />
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold text-black mb-2">
                                            Packaging
                                        </h3>
                                        <p className="text-black/50 leading-relaxed">
                                            Every parcel is packed carefully by
                                            hand to ensure your products arrive{" "}
                                            <strong>
                                                safely and beautifully
                                            </strong>
                                            .
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-16 pt-10 border-t border-gray-200 text-center">
                                <p className="text-2xl text-black/75 font-medium italic flex items-center justify-center gap-3">
                                    <FaHeart className="text-cream" />
                                    We don’t just ship products — we deliver
                                    comfort
                                    <FaHeart className="text-cream" />
                                </p>
                            </div>
                        </div>
                    </div>

                    <p className="text-center text-gray mt-12">
                        Questions about shipping? Just drop us a message at
                        support@hyggecotton.dk
                    </p>
                </div>
            </div>
        </div>
    );
};

export default ShippingInfo;
