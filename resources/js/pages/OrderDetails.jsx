import React from "react";
import { Link, Head } from "@inertiajs/react";
import { 
  ArrowLeft, 
  Package, 
  MapPin, 
  CreditCard, 
  Truck,
  Calendar,
  Download
} from "lucide-react";

const OrderDetails = ({ order }) => {
  if (!order) return null;

  return (
    <div className="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
      <Head title={`Order #${order.invoice_id}`} />
      
      <div className="mx-auto max-w-4xl">
        {/* Back Button */}
        <div className="mb-6">
          <Link
            href={route("user.profile")}
            className="inline-flex items-center text-gray-600 hover:text-[var(--color-red)] transition-colors"
          >
            <ArrowLeft size={20} className="mr-2" />
            Back to Orders
          </Link>
        </div>

        {/* Header Section */}
        <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
          <div className="p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
              <h1 className="text-2xl font-bold text-gray-900 flex items-center gap-3">
                Order #{order.invoice_id}
                <span className={`px-3 py-1 rounded-full text-sm font-medium ${
                  order.order_status?.slug === 'delivered' ? 'bg-green-100 text-green-800' :
                  order.order_status?.slug === 'cancelled' ? 'bg-red-100 text-red-800' :
                  'bg-blue-100 text-blue-800'
                }`}>
                  {order.order_status?.name}
                </span>
              </h1>
              <p className="text-gray-500 mt-2 flex items-center gap-2">
                <Calendar size={16} />
                Placed on {new Date(order.created_at).toLocaleDateString("en-US", {
                  year: "numeric",
                  month: "long",
                  day: "numeric",
                })}
              </p>
            </div>
            {/* <button className="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
              <Download size={16} />
              Invoice
            </button> */}
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content - Products */}
          <div className="lg:col-span-2 space-y-6">
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <div className="p-6 border-b border-gray-100">
                <h2 className="font-semibold text-lg flex items-center gap-2">
                  <Package size={20} className="text-[var(--color-red)]" />
                  Order Items
                </h2>
              </div>
              <div className="divide-y divide-gray-100">
                {order.order_products?.map((item) => (
                  <div key={item.id} className="p-6 flex gap-4">
                    <div className="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                      {item.front_image ? (
                        <img 
                          src={item.front_image} 
                          alt={item.product_name}
                          className="w-full h-full object-cover"
                        />
                      ) : (
                        <div className="w-full h-full flex items-center justify-center text-gray-400">
                          <Package size={24} />
                        </div>
                      )}
                    </div>
                    <div className="flex-1">
                      <h3 className="font-medium text-gray-900">{item.product_name}</h3>
                      {item.variants && (
                        <div className="text-sm text-gray-500 mt-1 space-y-1">
                          {/* Handle variants whether object or string (though backend casting should make it object) */}
                           {(() => {
                              try {
                                const v = typeof item.variants === 'string' ? JSON.parse(item.variants) : item.variants;
                                if (!v) return null;
                                return (
                                  <>
                                    {v.size_name && <p>Size: {v.size_name}</p>}
                                    {v.color_name && <p>Color: {v.color_name}</p>}
                                  </>
                                );
                              } catch (e) {
                                return <span>{String(item.variants)}</span>
                              }
                           })()}
                        </div>
                      )}
                      <div className="flex justify-between items-center mt-2">
                        <p className="text-sm text-gray-600">Qty: {item.qty}</p>
                        <p className="font-medium text-gray-900">
                          {order.currency_icon}{item.unit_price}
                        </p>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Payment Info */}
             <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <div className="p-6 border-b border-gray-100">
                <h2 className="font-semibold text-lg flex items-center gap-2">
                  <CreditCard size={20} className="text-[var(--color-red)]" />
                  Payment Summary
                </h2>
              </div>
              <div className="p-6 space-y-3">
                <div className="flex justify-between text-gray-600">
                  <span>Subtotal</span>
                  <span>{order.currency_icon}{order.sub_total}</span>
                </div>
                <div className="flex justify-between text-gray-600">
                  <span>Shipping Cost</span>
                  <span>
                    {(() => {
                        if (!order.shipping_method) return "Free Shipping";
                        try {
                             const sm = typeof order.shipping_method === 'string' ? JSON.parse(order.shipping_method) : order.shipping_method;
                             return sm.cost ? `${order.currency_icon}${sm.cost}` : "Free Shipping";
                        } catch (e) {
                            return order.shipping_method;
                        }
                    })()}
                  </span>
                </div>
                {order.coupon && (
                   <div className="flex justify-between text-green-600">
                    <span>Discount (Coupon)</span>
                    <span>
                      {(() => {
                         try {
                             const c = typeof order.coupon === 'string' ? JSON.parse(order.coupon) : order.coupon;
                             if (Array.isArray(c) && c.length === 0) return null;
                             // Assuming coupon object has amount or similar, adapt based on actual structure. 
                             // If it is just an empty array in the example, we hide it.
                             return c ? `- ${order.currency_icon}${c.discount || 0}` : null; 
                         } catch (e) {
                             return null;
                         }
                      })()}
                    </span>
                  </div>
                )}
                <div className="pt-3 border-t border-gray-100 flex justify-between font-bold text-lg text-gray-900">
                  <span>Total</span>
                  <span>{order.currency_icon}{order.amount}</span>
                </div>
                <div className="mt-4 pt-4 border-t border-gray-100">
                   <p className="text-sm text-gray-600">
                    Payment Method: <span className="font-medium text-gray-900 capitalize">{order.payment_method}</span>
                   </p>
                   <p className="text-sm text-gray-600 mt-1">
                    Payment Status: <span className={`font-medium capitalize ${
                        String(order.payment_status) === '1' || order.payment_status === 'completed' ? 'text-green-600' : 'text-orange-600'
                    }`}>
                        {String(order.payment_status) === '1' ? 'Completed' : (String(order.payment_status) === '0' ? 'Pending' : order.payment_status)}
                    </span>
                   </p>
                </div>
              </div>
            </div>
          </div>

          {/* Sidebar - Shipping Address */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
              <div className="p-6 border-b border-gray-100">
                <h2 className="font-semibold text-lg flex items-center gap-2">
                  <Truck size={20} className="text-[var(--color-red)]" />
                  Delivery Information
                </h2>
              </div>
              <div className="p-6">
                <div className="flex items-start gap-3 mb-4">
                  <MapPin size={20} className="text-gray-400 mt-1" />
                  <div>
                    <h4 className="font-medium text-gray-900">Shipping Address</h4>
                    {order.order_address ? (
                        <p className="text-gray-600 text-sm mt-1 leading-relaxed">
                        {order.order_address.user_name}<br />
                        {order.order_address.user_address}<br />
                        {order.order_address.city}, {order.order_address.zip_code}<br />
                        {order.order_address.country}
                        <br />
                        <span className="block mt-2 text-gray-500">{order.order_address.user_phone}</span>
                        <span className="block text-gray-500">{order.order_address.user_email}</span>
                        </p>
                    ) : (
                        <p className="text-gray-500 text-sm italic">Address details not available</p>
                    )}
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default OrderDetails;
