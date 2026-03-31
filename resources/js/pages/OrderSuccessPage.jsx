import React, { useEffect } from 'react';
import { Link, Head } from '@inertiajs/react';
import useCartStore from '../stores/cartStore';

const OrderSuccessPage = ({ order_id }) => {
    const { clearCart } = useCartStore();

    useEffect(() => {
        // Clear client-side cart when reaching the success page
        clearCart();
    }, [clearCart]);

    return (
        <div className="min-h-screen bg-dark1 flex items-center justify-center py-12 px-4">
            <Head title="Order Successful" />
            <div className="max-w-md w-full bg-dark2 rounded-2xl shadow-2xl p-8 text-center border border-gray/10">
                <div className="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg className="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h1 className="text-3xl font-bold text-cream mb-2">
                    Order Successful!
                </h1>
                
                {order_id && (
                    <p className="text-red font-semibold mb-4 tracking-wide uppercase text-sm">
                        Order ID: #{order_id}
                    </p>
                )}
                
                <p className="text-gray mb-8 leading-relaxed">
                    Thank you for your purchase! Your order has been
                    successfully placed. You'll receive a confirmation email
                    with the details soon.
                </p>
                
                <div className="space-y-4">
                    <Link
                        href={route("home")}
                        className="block w-full bg-red text-white py-3 px-6 rounded-xl font-semibold hover:bg-red/90 transition-all duration-300 shadow-lg shadow-red/20 transform hover:-translate-y-1"
                    >
                        Return to Home
                    </Link>
                    
                    <p className="text-xs text-gray/50 uppercase tracking-widest pt-4">
                        Danish Gift Store
                    </p>
                </div>
            </div>
        </div>
    );
};

export default OrderSuccessPage;
