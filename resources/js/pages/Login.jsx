// Login.jsx
import React, { useState } from "react";
import { Eye, EyeOff, ArrowRight, Loader2 } from "lucide-react";
import { Link, useForm } from "@inertiajs/react";
import toast from "react-hot-toast";

const Login = () => {
    const [showPassword, setShowPassword] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post("/customer-login", {
            onFinish: () => reset("password"),
            onError: (err) => {
                if (err.email) toast.error(err.email);
            }
        });
    };

    return (
        <div className="min-h-[80vh] flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gray-50">
            <div className="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-lg border border-gray">
                {/* Header */}
                <div className="text-center">
                    <h2 className="text-3xl font-bold text-gray-900 font-pop">Welcome back</h2>
                    <p className="mt-2 text-gray-600">
                        Sign in to your Danish Souvenirs account
                    </p>
                </div>

                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
                    {/* Email */}
                    <div>
                        <label
                            htmlFor="email"
                            className="block text-sm font-medium text-gray-700 font-pop"
                        >
                            Email address
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autoComplete="email"
                            required
                            value={data.email}
                            onChange={(e) => setData("email", e.target.value)}
                            className={`mt-1 block w-full px-4 py-3 border rounded-lg outline-none transition font-pop ${
                                errors.email ? "border-red-500 ring-1 ring-red-500" : "border-gray focus:ring-2 focus:ring-red focus:border-red"
                            }`}
                            placeholder="you@example.com"
                        />
                        {errors.email && (
                            <p className="mt-1 text-xs text-red-600 font-pop italic">{errors.email}</p>
                        )}
                    </div>

                    {/* Password */}
                    <div>
                        <label
                            htmlFor="password"
                            className="block text-sm font-medium text-gray-700 font-pop"
                        >
                            Password
                        </label>
                        <div className="mt-1 relative">
                            <input
                                id="password"
                                name="password"
                                type={showPassword ? "text" : "password"}
                                autoComplete="current-password"
                                required
                                value={data.password}
                                onChange={(e) => setData("password", e.target.value)}
                                className={`block w-full px-4 py-3 border rounded-lg outline-none transition pr-11 font-pop ${
                                    errors.password ? "border-red-500 ring-1 ring-red-500" : "border-gray focus:ring-2 focus:ring-red focus:border-red"
                                }`}
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            >
                                {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
                            </button>
                        </div>
                        {errors.password && (
                            <p className="mt-1 text-xs text-red-600 font-pop italic">{errors.password}</p>
                        )}
                    </div>

                    {/* Remember & Forgot */}
                    <div className="flex items-center justify-between font-pop">
                        <div className="flex items-center">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                checked={data.remember}
                                onChange={(e) => setData("remember", e.target.checked)}
                                className="h-4 w-4 text-red focus:ring-red border-gray rounded cursor-pointer"
                            />
                            <label
                                htmlFor="remember"
                                className="ml-2 block text-sm text-gray-700 cursor-pointer"
                            >
                                Remember me
                            </label>
                        </div>

                        <div className="text-sm font-pop">
                            <Link
                                href="/customer/forgot-password"
                                className="font-medium text-red hover:text-red-800 transition"
                            >
                                Forgot password?
                            </Link>
                        </div>
                    </div>

                    {/* Submit */}
                    <div>
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-white bg-red hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red transition font-medium font-pop disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            {processing ? (
                                <>
                                    <Loader2 className="animate-spin" size={18} />
                                    Signing in...
                                </>
                            ) : (
                                <>
                                    Sign in
                                    <ArrowRight size={18} />
                                </>
                            )}
                        </button>
                    </div>
                </form>

                {/* Divider */}
                <div className="relative my-6 font-pop">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-gray" />
                    </div>
                    <div className="relative flex justify-center text-sm">
                        <span className="px-3 bg-white text-gray-500">
                            Or continue with
                        </span>
                    </div>
                </div>

                {/* Social buttons (placeholder) */}
                <div className="grid grid-cols-2 gap-4 font-pop">
                    <a
                        href={route('customer.social.login', 'google')}
                        className="w-full py-3 px-4 border border-gray rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium flex justify-center items-center gap-2"
                    >
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" className="w-5 h-5" alt="Google" />
                        Google
                    </a>
                    <a
                        href={route('customer.social.login', 'facebook')}
                        className="w-full py-3 px-4 border border-gray rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium flex justify-center items-center gap-2"
                    >
                         <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" className="w-5 h-5" alt="Facebook" />
                        Facebook
                    </a>
                </div>

                {/* Register link */}
                <p className="mt-8 text-center text-sm text-gray-600 font-pop">
                    Don't have an account?{" "}
                    <Link
                        href="/customer/register"
                        className="font-medium text-red hover:text-red-800 transition"
                    >
                        Create one now
                    </Link>
                </p>
            </div>
        </div>
    );
};

export default Login;
