// ResetPassword.jsx
import React, { useState } from "react";
import { Eye, EyeOff, ArrowRight, Loader2, Lock, KeyRound } from "lucide-react";
import { Link, useForm } from "@inertiajs/react";
import toast from "react-hot-toast";

const ResetPassword = ({ token, email }) => {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        email: email || "",
        token: token || "",
        password: "",
        password_confirmation: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("customer.password.update"), {
            onFinish: () => reset("password", "password_confirmation"),
            onError: (err) => {
                Object.values(err).forEach(msg => toast.error(msg));
            }
        });
    };

    return (
        <div className="min-h-[80vh] flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gray-50">
            <div className="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-lg border border-gray">
                {/* Header */}
                <div className="text-center">
                    <div className="mx-auto w-16 h-16 bg-red/10 rounded-full flex items-center justify-center mb-4">
                        <KeyRound className="text-red" size={32} />
                    </div>
                    <h2 className="text-3xl font-bold text-gray-900 font-pop">Reset Password</h2>
                    <p className="mt-2 text-gray-600">
                        Enter your new password below
                    </p>
                </div>

                <form className="mt-8 space-y-5" onSubmit={handleSubmit}>
                    {/* Email (readonly) */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 font-pop">Email address</label>
                        <div className="mt-1">
                            <input
                                type="email"
                                readOnly
                                value={data.email}
                                className="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 font-pop cursor-not-allowed"
                            />
                        </div>
                    </div>

                    {/* New Password */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 font-pop">New Password</label>
                        <div className="mt-1 relative">
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <Lock size={18} />
                            </div>
                            <input
                                type={showPassword ? "text" : "password"}
                                required
                                value={data.password}
                                onChange={(e) => setData("password", e.target.value)}
                                className={`block w-full pl-10 pr-11 py-3 border rounded-lg outline-none transition font-pop ${
                                    errors.password ? "border-red-500 ring-1 ring-red-500" : "border-gray focus:ring-2 focus:ring-red focus:border-red"
                                }`}
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            >
                                {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                            </button>
                        </div>
                        {errors.password && <p className="mt-1 text-xs text-red-600 font-pop italic">{errors.password}</p>}
                    </div>

                    {/* Confirm Password */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 font-pop">Confirm New Password</label>
                        <div className="mt-1 relative">
                            <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <Lock size={18} />
                            </div>
                            <input
                                type={showConfirmPassword ? "text" : "password"}
                                required
                                value={data.password_confirmation}
                                onChange={(e) => setData("password_confirmation", e.target.value)}
                                className={`block w-full pl-10 pr-11 py-3 border rounded-lg outline-none transition font-pop ${
                                    errors.password_confirmation ? "border-red-500 ring-1 ring-red-500" : "border-gray focus:ring-2 focus:ring-red focus:border-red"
                                }`}
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            >
                                {showConfirmPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                            </button>
                        </div>
                        {errors.password_confirmation && <p className="mt-1 text-xs text-red-600 font-pop italic">{errors.password_confirmation}</p>}
                    </div>

                    {/* Submit */}
                    <div className="pt-2">
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-white bg-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red transition font-medium font-pop disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            {processing ? (
                                <>
                                    <Loader2 className="animate-spin" size={18} />
                                    Resetting Password...
                                </>
                            ) : (
                                <>
                                    Reset Password
                                    <ArrowRight size={18} />
                                </>
                            )}
                        </button>
                    </div>
                </form>

                {/* Back to login link */}
                <p className="mt-8 text-center text-sm text-gray-600 font-pop">
                    Remember your password?{" "}
                    <Link
                        href={route("customer.login")}
                        className="font-medium text-red hover:text-red-800 transition"
                    >
                        Back to Sign In
                    </Link>
                </p>
            </div>
        </div>
    );
};

export default ResetPassword;
