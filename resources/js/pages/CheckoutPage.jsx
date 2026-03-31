// CheckoutPage.jsx
import React, { useEffect, useState } from 'react';
import { ArrowLeft, CreditCard, Truck, CheckCircle, Smartphone, DollarSign, Package } from 'lucide-react';
import { useForm, Link, Head, usePage } from '@inertiajs/react';
import useCartStore from '../stores/cartStore';

const CheckoutPage = ({ shipping_methods, pickup_methods, countries, customer_addresses }) => {
    const { settings, payment_methods = [] } = usePage().props;
    const cartItems = useCartStore(state => state.cartItems);
    const cartTotal = useCartStore(state => state.total);
    const subtotal = Number(cartTotal) || 0;

    const { data, setData, post, processing, errors, transform } = useForm({
        // UI Fields
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        street_address: '',
        city: '',
        zip_code: '',
        country: 'Denmark',
        
        // Selection
        shipping_method_id: '',
        payment_method: 'paypal', // default
        
        // Internal storage for full objects required by backend
        shipping_method_obj: null,
    });

    const [selectedShippingCost, setSelectedShippingCost] = useState(0);

    const enabledPaymentMethods = (payment_methods || []).filter(method => method.enabled);

    useEffect(() => {
        if (enabledPaymentMethods.length === 0) return;
        const enabledKeys = enabledPaymentMethods.map(method => method.key);
        if (!enabledKeys.includes(data.payment_method)) {
            setData('payment_method', enabledKeys[0]);
        }
    }, [payment_methods]);

    // Transform data before submit to match PaymentController expectations
    transform((data) => ({
        payment_method: data.payment_method,
        shipping_method: data.shipping_method_obj, // backend expects array/object for shipping_method
        shipping_address: {
            address: data.street_address,
            city: data.city,
            zip_code: data.zip_code,
            country: data.country
        },
        personal_info: {
            first_name: data.first_name,
            last_name: data.last_name,
            email: data.email,
            phone: data.phone
        }
    }));

    const handleShippingChange = (method) => {
        setSelectedShippingCost(Number(method.cost));
        setData(data => ({
            ...data,
            shipping_method_id: method.id,
            shipping_method_obj: method
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('checkout.store'));
    };

    const finalTotal = Number(subtotal) + (Number(selectedShippingCost) || 0);

    return (
        <div className="min-h-screen bg-gray py-8 px-4 sm:px-6 lg:px-8">
            <Head title="Checkout" />
            <div className="container">
                {/* Header */}
                <div className="mb-8">
                    <Link
                        href={route('cart.index')}
                        className="inline-flex items-center gap-2 text-gray-600 hover:text-red transition mb-4"
                    >
                        <ArrowLeft size={18} />
                        Back to Cart
                    </Link>
                    <h1 className="text-3xl sm:text-4xl font-bold text-gray-900">Checkout</h1>
                </div>

                <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12">
                    {/* Left - Forms */}
                    <div className="lg:col-span-8 space-y-10">
                        {/* Contact & Shipping */}
                        <section className="bg-white rounded-xl shadow-sm border border-gray p-6 sm:p-8">
                            <h2 className="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                                <Truck size={22} className="text-red" />
                                Shipping Information
                            </h2>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        First Name
                                    </label>
                                    <input
                                        type="text"
                                        value={data.first_name}
                                        onChange={e => setData('first_name', e.target.value)}
                                        className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                        placeholder="John"
                                    />
                                    {errors['personal_info.first_name'] && <p className="text-red-500 text-xs mt-1">{errors['personal_info.first_name']}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Last Name
                                    </label>
                                    <input
                                        type="text"
                                        value={data.last_name}
                                        onChange={e => setData('last_name', e.target.value)}
                                        className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                        placeholder="Doe"
                                    />
                                    {errors['personal_info.last_name'] && <p className="text-red-500 text-xs mt-1">{errors['personal_info.last_name']}</p>}
                                </div>
                            </div>

                            <div className="mt-6">
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>
                                <input
                                    type="email"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                    placeholder="your@email.com"
                                />
                                {errors['personal_info.email'] && <p className="text-red-500 text-xs mt-1">{errors['personal_info.email']}</p>}
                            </div>

                            <div className="mt-6">
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input
                                    type="tel"
                                    value={data.phone}
                                    onChange={e => setData('phone', e.target.value)}
                                    className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                    placeholder="+45 12 34 56 78"
                                />
                                {errors['personal_info.phone'] && <p className="text-red-500 text-xs mt-1">{errors['personal_info.phone']}</p>}
                            </div>

                            <div className="mt-8 border-t border-gray pt-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Shipping Address</h3>
                                <div className="space-y-6">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Street Address
                                        </label>
                                        <input
                                            type="text"
                                            value={data.street_address}
                                            onChange={e => setData('street_address', e.target.value)}
                                            className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                            placeholder="123 Main Street"
                                        />
                                        {errors['shipping_address.address'] && <p className="text-red-500 text-xs mt-1">{errors['shipping_address.address']}</p>}
                                    </div>

                                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                        <div className="sm:col-span-2">
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                City
                                            </label>
                                            <input
                                                type="text"
                                                value={data.city}
                                                onChange={e => setData('city', e.target.value)}
                                                className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                                placeholder="Copenhagen"
                                            />
                                            {errors['shipping_address.city'] && <p className="text-red-500 text-xs mt-1">{errors['shipping_address.city']}</p>}
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                                ZIP / Postal
                                            </label>
                                            <input
                                                type="text"
                                                value={data.zip_code}
                                                onChange={e => setData('zip_code', e.target.value)}
                                                className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition"
                                                placeholder="1050"
                                            />
                                            {errors['shipping_address.zip_code'] && <p className="text-red-500 text-xs mt-1">{errors['shipping_address.zip_code']}</p>}
                                        </div>
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1">
                                            Country
                                        </label>
                                        <select
                                            value={data.country}
                                            onChange={e => setData('country', e.target.value)}
                                            className="w-full px-4 py-3 border border-gray rounded-lg focus:ring-2 focus:ring-red focus:border-red outline-none transition bg-white"
                                        >
                                            {countries && Object.entries(countries).length > 0 ? (
                                                Object.entries(countries).map(([code, name]) => (
                                                    <option key={code} value={name}>{name}</option>
                                                ))
                                            ) : (
                                                 <option value="Denmark">Denmark</option>
                                            )}
                                        </select>
                                        {errors['shipping_address.country'] && <p className="text-red-500 text-xs mt-1">{errors['shipping_address.country']}</p>}
                                    </div>
                                </div>
                            </div>
                            
                            {/* Shipping Methods */}
                            <div className="mt-8 border-t border-gray pt-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Shipping Method</h3>
                                <div className="space-y-3">
                                    {shipping_methods.map((method) => (
                                        <label key={method.id} className={`flex items-center justify-between p-4 border rounded-lg cursor-pointer transition ${data.shipping_method_id === method.id ? 'border-red bg-red-50' : 'border-gray hover:border-red'}`}>
                                            <div className="flex items-center gap-3">
                                                <input
                                                    type="radio"
                                                    name="shipping_method"
                                                    value={method.id}
                                                    checked={data.shipping_method_id === method.id}
                                                    onChange={() => handleShippingChange(method)}
                                                    className="text-red focus:ring-red"
                                                />
                                                <span className="font-medium text-gray-900">{method.name}</span>
                                            </div>
                                            <span className="font-medium text-gray-900">{settings?.currency_icon || '€'}{Number(method.cost).toFixed(2)}</span>
                                        </label>
                                    ))}
                                    {errors.shipping_method && <p className="text-red-500 text-xs mt-1">{errors.shipping_method}</p>}
                                </div>
                            </div>
                        </section>

                        {/* Payment */}
                        <section className="bg-white rounded-xl shadow-sm border border-gray p-6 sm:p-8">
                            <h2 className="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                                <CreditCard size={22} className="text-red" />
                                Payment Method
                            </h2>

                            <div className="space-y-4">
                                {enabledPaymentMethods.length === 0 ? (
                                    <p className="text-sm text-gray-500">No payment methods are available right now.</p>
                                ) : (
                                    enabledPaymentMethods.map((method) => (
                                        <label
                                            key={method.key}
                                            className={`flex items-center gap-3 p-4 border rounded-lg cursor-pointer transition ${data.payment_method === method.key ? 'border-red bg-red-50' : 'border-gray hover:border-red'}`}
                                        >
                                            <input
                                                type="radio"
                                                name="payment"
                                                value={method.key}
                                                checked={data.payment_method === method.key}
                                                onChange={(e) => setData('payment_method', e.target.value)}
                                                className="w-5 h-5 text-red focus:ring-red"
                                            />
                                            <div>
                                                <div className="font-medium">{method.label}</div>
                                                {method.description && (
                                                    <div className="text-sm text-gray-500">{method.description}</div>
                                                )}
                                            </div>
                                        </label>
                                    ))
                                )}
                            </div>
                        </section>
                    </div>

                    {/* Right - Order Summary */}
                    <div className="lg:col-span-4">
                        <div className="bg-white rounded-xl shadow-sm border border-gray p-6 sm:p-8 sticky top-8">
                            <h2 className="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>

                            <div className="space-y-5 mb-8">
                                {cartItems.length === 0 ? (
                                    <p className="text-gray-500 text-center">Your cart is empty.</p>
                                ) : (
                                    cartItems.map((item) => (
                                        <div key={item.id} className="flex gap-4">
                                            <div className="w-16 h-16 flex-shrink-0 rounded-md overflow-hidden border border-gray">
                                                <img
                                                    src={item.product?.thumb_image ? `/${item.product.thumb_image}` : (item.image || '/placeholder.png')}
                                                    alt={item.product?.name || item.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="font-medium text-gray-900 line-clamp-2">{item.product?.name || item.name}</p>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    {item.options?.color_name && <span>{item.options.color_name}</span>}
                                                    {item.options?.color_name && item.options?.size_name && <span>, </span>}
                                                    {item.options?.size_name && <span>{item.options.size_name}</span>}
                                                </p>
                                                <p className="text-xs text-gray-500 mt-0.5">Qty: {item.quantity}</p>
                                                {parseFloat(item.options?.variant_total || 0) > 0 && (
                                                    <p className="text-[11px] font-bold text-gray-700 mt-0.5">
                                                        variant extra : + {settings?.currency_icon || 'DKK.'}{parseFloat(item.options.variant_total).toFixed(2)}
                                                    </p>
                                                )}
                                                <p className="text-sm font-medium text-red mt-1">
                                                    {settings?.currency_icon || '€'}
                                                    {((parseFloat(item.price && item.price > 0 ? item.price : (item.product?.offer_price && item.product?.offer_price > 0 ? item.product?.offer_price : (item.product?.price || 0))) + parseFloat(item.options?.variant_total || 0)) * item.quantity).toFixed(2)}
                                                </p>
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>

                            <div className="space-y-4 border-t border-gray pt-6">
                                <div className="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>{settings?.currency_icon || 'DKK.'}{subtotal.toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span className={selectedShippingCost === 0 ? "text-green-600 font-medium" : ""}>
                                        {selectedShippingCost === 0 ? 'FREE' : `${settings?.currency_icon || 'DKK.'}${selectedShippingCost.toFixed(2)}`}
                                    </span>
                                </div>
                                <div className="flex justify-between text-lg font-bold text-gray-900 border-t border-gray pt-4">
                                    <span>Total</span>
                                    <span>{settings?.currency_icon || 'DKK.'}{finalTotal.toFixed(2)}</span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={processing || cartItems.length === 0}
                                className={`w-full mt-8 bg-red text-white py-4 rounded-lg font-medium text-lg hover:bg-red-800 transition duration-200 shadow-md flex items-center justify-center gap-2 ${processing || cartItems.length === 0 ? 'opacity-50 cursor-not-allowed' : ''}`}
                            >
                                <CheckCircle size={20} />
                                {processing ? 'Processing...' : 'Place Order'}
                            </button>

                            <p className="text-center text-sm text-gray-500 mt-6">
                                Secure payment • 30-day returns • Encrypted checkout
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default CheckoutPage;
