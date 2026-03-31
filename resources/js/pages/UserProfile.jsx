// UserProfile.jsx
import React, { useState } from "react";
import {
  User,
  Package,
  MapPin,
  Settings,
  LogOut,
  Edit,
  ChevronRight,
  Loader2,
  Camera,
  Lock,
} from "lucide-react";
import { Link, useForm, usePage } from "@inertiajs/react";

  const UserProfile = ({ totalOrder, ordersData, userData }) => {
  const [activeTab, setActiveTab] = useState("overview");
  const { auth, flash } = usePage().props;
  const [showFlash, setShowFlash] = useState(false);

  React.useEffect(() => {
    if (flash?.success) {
      setShowFlash(true);
      const timer = setTimeout(() => setShowFlash(false), 3000);
      return () => clearTimeout(timer);
    }
  }, [flash]);

  // Logout Form
  const { post: logoutPost, processing: logoutProcessing } = useForm();

  const handleLogout = (e) => {
    e.preventDefault();
    logoutPost(route("customer.logout"));
  };

  // Profile Update Form
  const { 
    data: profileData, 
    setData: setProfileData, 
    post: profilePost, 
    processing: profileProcessing, 
    errors: profileErrors,
    reset: resetProfile
  } = useForm({
    _method: 'PUT',
    name: userData.name || "",
    username: userData.username || "",
    email: userData.email || "",
    phone: userData.phone || "",
    address: userData.address || "",
    image: null,
  });

  const [imagePreview, setImagePreview] = useState(userData.image);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setProfileData("image", file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleProfileUpdate = (e) => {
    e.preventDefault();
    profilePost(route("update.profile"), {
        onSuccess: () => {
             // Optional: Show toast or success message
        }
    });
  };

  // Password Update Form
  const { 
    data: passwordData, 
    setData: setPasswordData, 
    put: passwordPut, 
    processing: passwordProcessing, 
    errors: passwordErrors,
    reset: resetPassword
  } = useForm({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
  });

  const handlePasswordUpdate = (e) => {
    e.preventDefault();
    passwordPut(route("update.password"), {
      onSuccess: () => resetPassword(),
    });
  };


  const tabs = [
    { id: "overview", label: "Overview", icon: User },
    { id: "orders", label: "Orders", icon: Package },
    // { id: "addresses", label: "Addresses", icon: MapPin }, // Merged into Profile/Settings for simplicity or keep separate if needed
    { id: "settings", label: "Edit Profile", icon: Settings },
    { id: "security", label: "Security", icon: Lock },
  ];

  return (
    <div className="min-h-screen bg-gray py-8 px-4 sm:px-6 lg:px-8 relative">
      <div className="mx-auto max-w-5xl">
        {/* Profile Header */}
        <div className="bg-white rounded-xl shadow-sm border border-[var(--color-gray)] overflow-hidden mb-8">
          <div className="p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div className="relative group">
              <img
                src={imagePreview || "https://media.istockphoto.com/id/2156807988/vector/simple-gray-avatar-icons-representing-male-and-female-profiles-vector-minimalist-design-with.jpg?s=612x612&w=0&k=20&c=xi7g5_9VBSWgntTZ-OQNS74d0oOvUnDGxjxUL_LdJUM="}
                alt={userData.name}
                className="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-md bg-gray-100"
              />
               {/* Clickable area for image upload if in edit mode, or just a visual indicator */}
               {activeTab === 'settings' && (
                   <label htmlFor="profile-image-upload" className="absolute bottom-0 right-0 bg-[var(--color-red)] text-white p-2 rounded-full shadow cursor-pointer hover:bg-red-700 transition">
                       <Camera size={16} />
                       <input 
                           id="profile-image-upload"
                           type="file" 
                           accept="image/*"
                           className="hidden"
                           onChange={handleImageChange}
                       />
                   </label>
               )}
            </div>

            <div className="text-center sm:text-left flex-1">
              <h1 className="text-2xl sm:text-3xl font-bold text-gray-900">
                {userData.name}
              </h1>
              <p className="text-gray-600 mt-1">{userData.email}</p>
              <p className="text-sm text-gray-500 mt-1">{userData.phone}</p>
            </div>

            <div className="sm:ml-auto mt-4 sm:mt-0">
              <button 
                onClick={handleLogout}
                disabled={logoutProcessing}
                className="flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition shadow-sm disabled:opacity-50 font-medium"
              >
                {logoutProcessing ? <Loader2 className="animate-spin" size={18} /> : <LogOut size={18} />}
                Sign Out
              </button>
            </div>
          </div>
        </div>

        {/* Tabs Navigation */}
        <div className="bg-white rounded-xl shadow-sm border border-[var(--color-gray)] overflow-hidden flex flex-col lg:flex-row min-h-[600px]">
          
            {/* Sidebar Navigation */}
            <div className="hidden lg:block w-72 border-r border-[var(--color-gray)] bg-gray-50/50 p-4">
              <nav className="space-y-1">
                {tabs.map((tab) => (
                  <button
                    key={tab.id}
                    onClick={() => setActiveTab(tab.id)}
                    className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition font-medium ${
                      activeTab === tab.id
                        ? "bg-white text-[var(--color-red)] shadow-sm border border-gray-100"
                        : "text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                    }`}
                  >
                    <tab.icon size={18} />
                    {tab.label}
                  </button>
                ))}
              </nav>
            </div>

             {/* Mobile Tabs */}
            <div className="lg:hidden border-b border-[var(--color-gray)] overflow-x-auto">
                <div className="flex p-2 gap-2 min-w-max">
                {tabs.map((tab) => (
                    <button
                        key={tab.id}
                        onClick={() => setActiveTab(tab.id)}
                        className={`flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition ${
                        activeTab === tab.id
                            ? "bg-[var(--color-red)] text-white shadow"
                            : "bg-gray-100 text-gray-600"
                        }`}
                    >
                        <tab.icon size={16} />
                        {tab.label}
                    </button>
                    ))}
                </div>
            </div>

            {/* Content Area */}
            <div className="flex-1 p-6 sm:p-8 lg:p-10">
              
              {/* === OVERVIEW TAB === */}
              {activeTab === "overview" && (
                <div className="space-y-8 animate-fade-in">
                  <div>
                    <h2 className="text-2xl font-bold text-gray-900 mb-6">Account Overview</h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div className="bg-gradient-to-br from-[var(--color-red)] to-red-600 p-6 rounded-2xl text-white shadow-lg shadow-red-200">
                        <div className="text-red-100 font-medium mb-1">Total Orders</div>
                        <div className="text-4xl font-bold">{totalOrder}</div>
                        </div>
                        
                        <div className="bg-white p-6 rounded-xl border border-dashed border-gray-300 flex flex-col justify-center items-center text-center">
                            <span className="text-gray-400 mb-2">My Wishlist</span>
                            <span className="text-2xl font-semibold text-gray-700">0</span>
                        </div>
                    </div>
                  </div>

                  <div>
                    <div className="flex justify-between items-center mb-4">
                        <h3 className="text-xl font-bold text-gray-900">Recent Orders</h3>
                        <button onClick={() => setActiveTab('orders')} className="text-[var(--color-red)] text-sm font-medium hover:underline">View All</button>
                    </div>
                    
                    <div className="space-y-4">
                      {ordersData?.slice(0, 3).map((order) => (
                        <div
                          key={order.id}
                          className="flex justify-between items-center p-4 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition"
                        >
                          <div className="flex items-center gap-4">
                             <div className="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-[var(--color-red)]">
                                 <Package size={20}/>
                             </div>
                             <div>
                                <p className="font-bold text-gray-900">#{order.invoice_id}</p>
                                <p className="text-sm text-gray-500">{order.payment_method}</p>
                             </div>
                          </div>
                          <div className="text-right">
                            <p className="font-bold text-gray-900">
                              {order.currency_icon}{order.amount}
                            </p>
                            <span className={`text-xs font-medium px-2 py-0.5 rounded-full ${
                                order.order_status?.slug === 'delivered' ? 'bg-green-100 text-green-700' : 
                                order.order_status?.slug === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'
                            }`}>
                              {order.order_status?.name}
                            </span>
                          </div>
                        </div>
                      ))}
                      {(!ordersData || ordersData.length === 0) && (
                        <div className="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <Package className="mx-auto text-gray-300 mb-2" size={32} />
                            <p className="text-gray-500">No recent orders found.</p>
                        </div>
                      )}
                    </div>
                  </div>
                </div>
              )}

              {/* === ORDERS TAB === */}
              {activeTab === "orders" && (
                <div className="animate-fade-in">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">Order History</h2>
                  <div className="space-y-5">
                    {ordersData?.map((order) => (
                      <div
                        key={order.id}
                        className="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-[var(--color-red)] hover:shadow-md transition-all duration-200"
                      >
                        <div className="p-5 flex flex-col sm:flex-row justify-between items-start gap-4">
                          <div className="flex-1">
                             <div className="flex items-center gap-3 mb-2">
                                <span className="font-bold text-lg text-gray-900">#{order.invoice_id}</span>
                                <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold ${
                                    order.order_status?.slug === 'delivered' ? 'bg-green-100 text-green-700' : 
                                    order.order_status?.slug === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'
                                }`}>
                                    {order.order_status?.name}
                                </span>
                             </div>
                             <div className="text-sm text-gray-500 flex flex-wrap gap-x-6 gap-y-1">
                                <span><span className="font-medium text-gray-700">Date:</span> {new Date(order.created_at || Date.now()).toLocaleDateString()}</span>
                                <span><span className="font-medium text-gray-700">Payment:</span> {order.payment_method}</span>
                             </div>
                          </div>
                          
                          <div className="flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                             <span className="text-lg font-bold text-gray-900">
                                {order.currency_icon}{order.amount}
                             </span>
                             <Link 
                                href={route('user.order.details', order.id)}
                                className="flex items-center gap-1 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[var(--color-red)] transition"
                             >
                                Details 
                                <ChevronRight size={16} />
                             </Link>
                          </div>
                        </div>
                        {/* Optional: Preview of items could go here */}
                      </div>
                    ))}
                    {(!ordersData || ordersData.length === 0) && (
                       <div className="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <Package className="mx-auto text-gray-300 mb-4" size={48} />
                            <h3 className="text-lg font-medium text-gray-900">No orders yet</h3>
                            <p className="text-gray-500 max-w-sm mx-auto mt-1">When you place orders, they will appear here for you to track and manage.</p>
                       </div>
                    )}
                  </div>
                </div>
              )}

              {/* === EDIT PROFILE TAB === */}
              {activeTab === "settings" && (
                <div className="animate-fade-in max-w-2xl">
                  <h2 className="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h2>

                  <form onSubmit={handleProfileUpdate} className="space-y-6">
                    
                    {/* Image Upload Section in Form */}
                    {/* <div className="flex items-center gap-6 mb-6">
                        <div className="relative">
                            <img
                                src={imagePreview || "https://media.istockphoto.com/id/2156807988/vector/simple-gray-avatar-icons-representing-male-and-female-profiles-vector-minimalist-design-with.jpg?s=612x612&w=0&k=20&c=xi7g5_9VBSWgntTZ-OQNS74d0oOvUnDGxjxUL_LdJUM="}
                                alt="Profile Preview"
                                className="w-20 h-20 rounded-full object-cover border-2 border-gray-200"
                            />
                            <label 
                                htmlFor="form-image-upload" 
                                className="absolute bottom-0 right-0 bg-white border border-gray-300 p-1.5 rounded-full shadow-sm cursor-pointer hover:bg-gray-50 transition"
                            >
                                <Camera size={14} className="text-gray-600" />
                                <input
                                    id="form-image-upload"
                                    type="file"
                                    accept="image/*"
                                    className="hidden"
                                    onChange={(e) => {
                                        const file = e.target.files[0];
                                        if (file) {
                                            setProfileData('image', file);
                                            setImagePreview(URL.createObjectURL(file));
                                        }
                                    }}
                                />
                            </label>
                        </div>
                        <div>
                            <p className="text-sm font-medium text-gray-900">Profile Photo</p>
                            <p className="text-xs text-gray-500 mt-1">Accepts JPG, PNG or GIF. Max 2MB.</p>
                            {profileErrors.image && <p className="text-red-500 text-xs mt-1">{profileErrors.image}</p>}
                        </div>
                    </div> */}

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Full Name</label>
                            <input
                                type="text"
                                value={profileData.name}
                                onChange={(e) => setProfileData('name', e.target.value)}
                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                placeholder="Your Name"
                            />
                            {profileErrors.name && <p className="text-red-500 text-xs mt-1">{profileErrors.name}</p>}
                        </div>
                        
                        <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Username</label>
                            <input
                                type="text"
                                value={profileData.username}
                                onChange={(e) => setProfileData('username', e.target.value)}
                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                placeholder="username"
                            />
                             {profileErrors.username && <p className="text-red-500 text-xs mt-1">{profileErrors.username}</p>}
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Email Address</label>
                            <input
                                type="email"
                                value={profileData.email}
                                onChange={(e) => setProfileData('email', e.target.value)}
                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                disabled // Keep email disabled if logic requires verification, or enable if supported
                            />
                             {profileErrors.email && <p className="text-red-500 text-xs mt-1">{profileErrors.email}</p>}
                        </div>

                         <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Phone Number</label>
                            <input
                                type="text"
                                value={profileData.phone}
                                onChange={(e) => setProfileData('phone', e.target.value)}
                                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                placeholder="+1 234 567 890"
                            />
                             {profileErrors.phone && <p className="text-red-500 text-xs mt-1">{profileErrors.phone}</p>}
                        </div>
                    </div>

                     <div className="space-y-2">
                        <label className="block text-sm font-semibold text-gray-700">Address</label>
                        <textarea
                            value={profileData.address}
                            onChange={(e) => setProfileData('address', e.target.value)}
                            rows="3"
                            className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition resize-none"
                            placeholder="Your full address..."
                        ></textarea>
                         {profileErrors.address && <p className="text-red-500 text-xs mt-1">{profileErrors.address}</p>}
                    </div>

                    <div className="pt-4">
                        <button 
                            type="submit" 
                            disabled={profileProcessing}
                            className="bg-[var(--color-red)] text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition shadow-lg shadow-red-200 disabled:opacity-70 flex items-center gap-2"
                        >
                            {profileProcessing && <Loader2 className="animate-spin" size={20} />}
                            Save Changes
                        </button>
                    </div>

                  </form>
                </div>
              )}

              {/* === SECURITY TAB === */}
               {activeTab === "security" && (
                <div className="animate-fade-in max-w-xl">
                    <h2 className="text-2xl font-bold text-gray-900 mb-2">Security Settings</h2>
                    <p className="text-gray-500 mb-8">Update your password to keep your account secure.</p>

                    <form onSubmit={handlePasswordUpdate} className="space-y-6">
                        <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Current Password</label>
                            <div className="relative">
                                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                                <input
                                    type="password"
                                    value={passwordData.current_password}
                                    onChange={(e) => setPasswordData('current_password', e.target.value)}
                                    className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                    placeholder="Enter current password"
                                />
                            </div>
                            {passwordErrors.current_password && <p className="text-red-500 text-xs mt-1">{passwordErrors.current_password}</p>}
                        </div>

                         <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">New Password</label>
                             <div className="relative">
                                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                                <input
                                    type="password"
                                    value={passwordData.new_password}
                                    onChange={(e) => setPasswordData('new_password', e.target.value)}
                                    className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                    placeholder="Enter new password (min. 8 chars)"
                                />
                            </div>
                            {passwordErrors.new_password && <p className="text-red-500 text-xs mt-1">{passwordErrors.new_password}</p>}
                        </div>

                         <div className="space-y-2">
                            <label className="block text-sm font-semibold text-gray-700">Confirm New Password</label>
                             <div className="relative">
                                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                                <input
                                    type="password"
                                    value={passwordData.new_password_confirmation}
                                    onChange={(e) => setPasswordData('new_password_confirmation', e.target.value)}
                                    className="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--color-red)] focus:border-[var(--color-red)] outline-none transition"
                                    placeholder="Confirm new password"
                                />
                            </div>
                        </div>

                        <div className="pt-4">
                            <button 
                                type="submit" 
                                disabled={passwordProcessing}
                                className="bg-gray-900 text-white px-8 py-3 rounded-lg font-bold hover:bg-gray-800 transition shadow-lg disabled:opacity-70 flex items-center gap-2"
                            >
                                {passwordProcessing && <Loader2 className="animate-spin" size={20} />}
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
               )}

            </div>
        </div>
      </div>
    </div>
  );
};

export default UserProfile;
