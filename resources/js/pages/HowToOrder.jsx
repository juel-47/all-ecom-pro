 
import React from "react";
import {
    FaSearch,
    FaUpload,
    FaShoppingCart,
    FaTruck,
    FaMapMarkedAlt,
    FaRulerCombined,
    FaHeart,
} from "react-icons/fa";

const HowToOrder = () => {
    return (
        <div className="min-h-screen bg-gray-100 py-20 ">
            <div className="container px-4 2xl:px-20">
                <div className="max-w-6xl mx-auto">
                    {/* How to Order - 4 Steps */}
                    <section className="mb-20">
                        <div className="text-center mb-8">
                            <h2 className="text-4xl font-bold text-center mb-3 text-cream">
                                How to Order
                            </h2>
                            <p className="text-sm lg:text-lg text-black">
                                From idea to doorstep — simple, personal, and
                                made with love ♡
                            </p>
                        </div>
                        <div className="grid md:grid-cols-4 gap-8">
                            {[
                                {
                                    icon: (
                                        <FaSearch className="text-5xl text-red" />
                                    ),
                                    title: "Browse Collection",
                                    desc: "Explore our premium T-Shirts, Hoodies, and Bags made from soft, sustainable cotton.",
                                },
                                {
                                    icon: (
                                        <FaUpload className="text-5xl text-red" />
                                    ),
                                    title: "Customize",
                                    desc: "Use our easy tool to upload your design, add text, or create something truly yours.",
                                },
                                {
                                    icon: (
                                        <FaShoppingCart className="text-5xl text-red" />
                                    ),
                                    title: "Add to Cart & Pay",
                                    desc: "Review your creation and complete secure payment in just a few clicks.",
                                },
                                {
                                    icon: (
                                        <FaTruck className="text-5xl text-red" />
                                    ),
                                    title: "We Make & Ship",
                                    desc: "We handcraft your custom piece in Copenhagen and ship it straight to your door.",
                                },
                            ].map((step, i) => (
                                <div key={i} className="text-center group">
                                    <div className="relative mb-8">
                                        <div className="w-28 h-28 mx-auto rounded-full bg-red-200 flex items-center justify-center  transition-all duration-300 shadow-xl">
                                            {step.icon}
                                        </div>
                                    </div>
                                    <h3 className="text-xl font-bold text-black mb-3">
                                        {step.title}
                                    </h3>
                                    <p className="text-black/75 leading-rselaxed text-sm">
                                        {step.desc}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Order Tracking */}
                    <section className="mb-4 bg-dark2 rounded-3xl p-10 md:p-16">
                        <div className="flex items-start gap-8 max-w-4xl mx-auto">
                            <div className="w-20 h-20 bg-white rounded-full shrink-0 flex items-center justify-center shadow-xl">
                                <FaMapMarkedAlt className="text-4xl text-red" />
                            </div>
                            <div>
                                <h2 className="text-3xl font-bold text-cream mb-2">
                                    Order Tracking
                                </h2>
                                <p className="text-sm text-black/50 leading-relaxed">
                                    Once your order ships, you’ll receive an{" "}
                                    <strong>
                                        email with a tracking number
                                    </strong>
                                    .
                                    <br />
                                    You can also{" "}
                                    <strong>
                                        log in to your Hygge Cotton account
                                    </strong>{" "}
                                    anytime to see real-time delivery updates
                                    and know exactly when your comfort is
                                    arriving.
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Size Guide */}
                    <section className="bg-white rounded-3xl shadow-2xl p-10 md:p-16">
                        <div className="flex items-start gap-8 max-w-4xl mx-auto">
                            <div className="w-20 h-20 bg-gray rounded-full shrink-0 flex items-center justify-center shadow-xl">
                                <FaRulerCombined className="text-4xl text-dark2" />
                            </div>
                            <div>
                                <h2 className="text-3xl font-bold text-cream mb-6">
                                    Size Guide
                                </h2>
                                <p className="text-sm text-black/50 leading-relaxed">
                                    We want your products to fit
                                    <strong>perfectly</strong> — just like a
                                    warm hug. Check the
                                    <strong>detailed size chart</strong> on each
                                    product page (all measurements in
                                    centimeters).
                                    <br />
                                    <span className="text-sm text-black/50 italic">
                                        Note: Small variations (±1–2 cm) may
                                        occur due to our handmade production —
                                        each piece is unique ♡
                                    </span>
                                </p>
                                <a
                                    href="/size-guide"
                                    className="inline-block mt-6 bg-red text-white px-8 py-4 rounded-full font-semibold transition shadow-lg"
                                >
                                    View Full Size Guide →
                                </a>
                            </div>
                        </div>
                    </section>

                    {/* Final CTA */}
                    <div className="text-center mt-20">
                        <p className="text-3xl font-medium text-black/50 mb-8">
                            Ready to create something beautiful?
                        </p>
                        <a
                            href="/shop"
                            className="inline-block bg-red text-white px-12 py-5 rounded-full text-xl font-semibold   transition shadow-2xl transform hover:scale-105"
                        >
                            Start Shopping Now
                        </a>
                    </div>

                    {/* Footer Love */}
                    <p className="text-center text-gray mt-16 italic flex items-center justify-center gap-2">
                        Every order is made with care in Copenhagen{" "}
                        <FaHeart className="text-green-700" />
                    </p>
                </div>
            </div>
        </div>
    );
};

export default HowToOrder;
