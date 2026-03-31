// src/pages/ResendEmail.jsx
import React from "react";
import { Link, useForm, Head } from "@inertiajs/react";

const ResendEmail = ({ email }) => {
    const { data, setData, post, processing, wasSuccessful } = useForm({
        email: email || "",
    });

    const handleResend = (e) => {
        e.preventDefault();
        post(route('verification.resend'), {
            preserveScroll: true,
        });
    };

    return (
        <div className="flex h-screen w-full bg-dark1">
            <Head title="Verify Email" />
            <div className="w-full flex flex-col items-center justify-center px-6">
                <div className="md:w-96 w-full bg-dark2/50 backdrop-blur-sm rounded-2xl p-10 border border-cream/20 shadow-2xl">
                    {/* Icon */}
                    <div className="flex justify-center mb-8">
                        <div className="w-24 h-24 bg-red/20 rounded-full flex items-center justify-center">
                            <svg
                                width="48"
                                height="48"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M20 6L9 17L4 12"
                                    stroke="#ff4444"
                                    strokeWidth="3"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="#ff4444"
                                    strokeWidth="2"
                                    fill="none"
                                />
                            </svg>
                        </div>
                    </div>

                    <h2 className="text-3xl text-cream font-semibold text-center mb-4">
                        Check Your Email
                    </h2>

                    <p className="text-gray-800 text-center text-sm leading-relaxed mb-8">
                        We’ve sent a verification link to
                        <br />
                        <span className="text-cream font-medium">
                            {data.email || "your email"}
                        </span>
                        <br />
                        Please click the link in the email to verify your
                        account.
                    </p>

                    <form onSubmit={handleResend} className="space-y-4">
                        <button
                            type="submit"
                            disabled={processing}
                            className={`w-full h-12 rounded-full text-white font-medium transition-all ${
                                processing
                                    ? "bg-gray-600 cursor-not-allowed"
                                    : "bg-red hover:bg-red/90 cursor-pointer"
                            }`}
                        >
                            {processing
                                ? "Sending..."
                                : "Resend Verification Email"}
                        </button>

                        <div className="text-center">
                            <Link
                                href={route('customer.login')}
                                className="text-gray-800 hover:underline text-sm"
                            >
                                Back to Sign In
                            </Link>
                        </div>
                    </form>

                    <p className="text-gray-500 text-xs text-center mt-8">
                        Didn’t receive the email? Check your spam folder or try
                        resending.
                    </p>
                </div>
            </div>
        </div>
    );
};

export default ResendEmail;
