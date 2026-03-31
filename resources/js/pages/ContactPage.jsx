import React from "react";

import { Mail, Phone, MapPin, Send } from "lucide-react";
import { useForm, usePage } from "@inertiajs/react";
import { toast } from "react-hot-toast";

const ContactPage = () => {
    const { props } = usePage();
    const { settings, flash } = props;
    const address = settings?.contact_address || "";
    const phone = settings?.contact_phone || "";
    const email = settings?.contact_email || "";

    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        subject: "",
        message: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("contact-form.submit"), {
            onSuccess: () => {
                reset();
            },
            onError: () => {
                toast.error("Failed to send message. Please check the errors.");
            },
        });
    };

    return (
        <>
            <div className="relative min-h-screen bg-gray-50    pt-25 pb-20 ">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="text-center mb-16">
                        <h1 className="text-5xl font-bold mb-4 ">
                            Get In Touch
                        </h1>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            We’d love to hear from you! Whether you have a
                            question about our education expo or need assistance
                            with your study abroad journey, we’re here to help.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        {/* Left - Contact Info Cards */}
                        <div className="lg:col-span-1 space-y-8">
                            <div className="bg-white flex gap-4 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                                <div className="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-6">
                                    <MapPin size={28} className="text-red" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-semibold mb-1 text-black/70">
                                        Visit Us
                                    </h3>
                                    <p className="text-gray-600">{address}</p>
                                </div>
                            </div>

                            <div className="bg-white flex gap-4 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                                <div className="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-6">
                                    <Phone size={28} className="text-red" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-semibold mb-1 text-black/70">
                                        Call Us
                                    </h3>
                                    <p className="text-gray-600">{phone}</p>
                                </div>
                            </div>

                            <div className="bg-white flex gap-4 p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                                <div className="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-6">
                                    <Mail size={28} className="text-red" />
                                </div>
                                <div>
                                    <h3 className="text-xl font-semibold mb-1 text-black/70">
                                        Email Us
                                    </h3>
                                    <p className="text-gray-600">{email}</p>
                                </div>
                            </div>
                        </div>

                        {/* Right - Contact Form */}
                        <div className="lg:col-span-2">
                            <form 
                                onSubmit={handleSubmit}
                                className="bg-white p-8 rounded-2xl shadow-md border border-gray-100"
                            >
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <label className="block text-gray-700 font-medium mb-2">
                                            First Name
                                        </label>
                                        <input
                                            type="text"
                                            className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.first_name ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                            placeholder="First name"
                                            value={data.first_name}
                                            onChange={e => setData("first_name", e.target.value)}
                                            required
                                        />
                                        {errors.first_name && <p className="text-red-500 text-sm mt-1">{errors.first_name}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-gray-700 font-medium mb-2">
                                            Last Name
                                        </label>
                                        <input
                                            type="text"
                                            className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.last_name ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                            placeholder="Last name"
                                            value={data.last_name}
                                            onChange={e => setData("last_name", e.target.value)}
                                            required
                                        />
                                        {errors.last_name && <p className="text-red-500 text-sm mt-1">{errors.last_name}</p>}
                                    </div>
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 font-medium mb-2">
                                        Phone Number
                                    </label>
                                    <input
                                        type="tel"
                                        className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.phone ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                        placeholder="+880 1XXXXXXXXX"
                                        value={data.phone}
                                        onChange={e => setData("phone", e.target.value)}
                                        required
                                    />
                                    {errors.phone && <p className="text-red-500 text-sm mt-1">{errors.phone}</p>}
                                </div>
                                <div className="mb-4">
                                    <label className="block text-gray-700 font-medium mb-2">
                                        Email Address
                                    </label>
                                    <input
                                        type="email"
                                        className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.email ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                        placeholder="your@email.com"
                                        value={data.email}
                                        onChange={e => setData("email", e.target.value)}
                                        required
                                    />
                                    {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
                                </div>
                                <div className="mb-4">
                                    <label className="block text-gray-700 font-medium mb-2">
                                        Subject
                                    </label>
                                    <input
                                        type="text"
                                        className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.subject ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                        placeholder="Subject"
                                        value={data.subject}
                                        onChange={e => setData("subject", e.target.value)}
                                    />
                                    {errors.subject && <p className="text-red-500 text-sm mt-1">{errors.subject}</p>}
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 font-medium mb-2">
                                        Your Message
                                    </label>
                                    <textarea
                                        rows={5}
                                        className={`w-full px-5 py-3 border rounded-lg focus:outline-none focus:ring-1 transition ${errors.message ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-red'}`}
                                        placeholder="Tell us how we can help you..."
                                        value={data.message}
                                        onChange={e => setData("message", e.target.value)}
                                        required
                                    ></textarea>
                                    {errors.message && <p className="text-red-500 text-sm mt-1">{errors.message}</p>}
                                </div>

                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="w-full md:w-auto px-10 py-4 bg-red text-white font-semibold rounded-lg  transition flex items-center justify-center gap-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <Send size={20} />
                                    {processing ? "Sending..." : "Send Message"}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default ContactPage;
