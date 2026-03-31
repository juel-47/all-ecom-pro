// pages/Returns.jsx  অথবা components/ReturnsPolicy.jsx
import React from "react";
import {
    FaUndoAlt,
    FaEnvelope,
    FaClock,
    FaHeart,
    FaBoxOpen,
} from "react-icons/fa";
import { usePage } from "@inertiajs/react";

const ReturnsPolicy = () => {
      const {props} = usePage();
      const {settings}=props;
      const constactEmail=settings?.contact_email || "";
    return (
        <div className="min-h-screen bg-gray py-20 ">
            <div className="container px-4 2xl:px-20">
                <div className="max-w-4xl mx-auto">
                    {/* Header */}
                    <div className="text-center mb-16">
                        <h1 className="text-xl lg:text-3xl font-bold text-cream mb-4">
                            Refunds & Returns
                        </h1>
                        <p className="text-sm lg:text-xl text-black/75">
                            Your happiness is our priority ♡
                        </p>
                    </div>

                    {/* Main Card */}
                    <div className="bg-white rounded-3xl shadow-2xl overflow-hidden">
                        <div className="bg-gray-300 py-12 text-center px-4">
                            <FaHeart className="text-6xl text-cream mx-auto mb-4" />
                            <p className="text-cream text-2xl font-medium">
                                We want you to love everything you buy from us
                            </p>
                        </div>

                        <div className="p-12 md:p-16 space-y-12">
                            {/* Main Policy */}
                            <div className="text-center max-w-3xl mx-auto">
                                <p className="text-lg text-black/50 leading-relaxed">
                                    If you receive a{" "}
                                    <strong>defective or incorrect item</strong>
                                    , we’re happy to offer a{" "}
                                    <strong>full refund or replacement</strong>{" "}
                                    — no questions asked.
                                </p>
                                <p className="text-lg text-black/50 mt-6">
                                    Custom-made products are{" "}
                                    <strong>non-refundable</strong> unless they
                                    arrive damaged or incorrect.
                                </p>
                            </div>

                            {/* Key Info Grid */}
                            <div className="grid md:grid-cols-3 gap-8 max-w-3xl mx-auto">
                                <div className="text-center">
                                    <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                        <FaClock className="text-3xl text-red" />
                                    </div>
                                    <h3 className="text-xl font-bold mb-3 text-black">
                                        Return Window
                                    </h3>
                                    <p className="text-black/50">
                                        Contact us within{" "}
                                        <strong>14 days</strong>
                                        of delivery
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                        <FaEnvelope className="text-3xl text-red " />
                                    </div>
                                    <h3 className="text-xl font-bold mb-3 text-black">
                                        How to Start
                                    </h3>
                                    <p className="text-gray">
                                        <a
                                            href={`mailto:${constactEmail}`}
                                            className="text-black/50  font-bold hover:underline"
                                        >
                                            {constactEmail}
                                        </a>
                                    </p>
                                </div>

                                <div className="text-center">
                                    <div className="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                        <FaUndoAlt className="text-3xl text-red" />
                                    </div>
                                    <h3 className="text-xl font-bold mb-3 text-black">
                                        Refund Time
                                    </h3>
                                    <p className="text-black/50">
                                        Processed within{" "}
                                        <strong>5–10 business days</strong> once
                                        approved
                                    </p>
                                </div>
                            </div>

                            {/* What to Include */}
                            <div className="bg-red-100 rounded-2xl p-8 text-center">
                                <FaBoxOpen className="text-4xl text-cream mx-auto mb-4" />
                                <p className="text-lg font-medium text-black/50">
                                    Please include your{" "}
                                    <strong>order number</strong> and{" "}
                                    <strong>photos</strong> if the item is
                                    damaged or incorrect.
                                </p>
                            </div>

                            {/* Final Message */}
                            <div className="pt-10 border-t border-gray-200 text-center">
                                <p className="text-2xl text-black/75 font-medium italic">
                                    We’re here to make things right — always.
                                    <br />
                                    <span className="text-black/75">
                                        Just reach out. We’ve got you.
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <p className="text-center text-gray mt-12 text-sm">
                        Questions? Email us anytime at hyggecotton2025@gmail.com
                    </p>
                </div>
            </div>
        </div>
    );
};

export default ReturnsPolicy;
