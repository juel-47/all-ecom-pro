<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="javascript:;">{{ Auth::user()->name }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="javascript:;">{{ limitText(Auth::user()->name, 2) }}</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="dropdown">
                <a href="{{ route('admin.dashboard') }}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            {{-- <li class="menu-header">E-Commerce</li> --}}
            @canany(['Manage Categories', 'Manage Products', 'Manage Orders', 'Manage Ecommerce', 'Manage Transaction',
                'Manage Website', 'Manage Blog'])
                <li class="menu-header">E-Commerce</li>
                @can('Manage Categories')
                    <li
                        class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                            <span>Manage Categories</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.category.index') }}">Category </a></li>
                            <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.sub-category.index') }}">Sub Category </a></li>
                            <li class="{{ setActive(['admin.child-category.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.child-category.index') }}">Child Category </a></li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Products')
                    <li
                        class="dropdown {{ setActive([
                            'admin.brand.*',
                            'admin.products.*',
                            'admin.products-image-gallery.*',
                            'admin.product-variant.*',
                            'admin.products-variant-item.*',
                            'admin.seller-product.*',
                            'admin.seller-product-pending.*',
                            'admin.reviews.index',
                            'admin.size.*',
                            'admin.color.*',
                            'admin.product-pending.index',
                            'admin.product_out_of_stock.index',
                        ]) }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box"></i>
                            <span>Manage Products</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.brand.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.brand.index') }}">Brands</a></li>
                            <li
                                class="{{ setActive([
                                    'admin.products.*',
                                    'admin.products-image-gallery.*',
                                    'admin.product-variant.*',
                                    'admin.products-variant-item.*',
                                ]) }}">
                                <a class="nav-link" href="{{ route('admin.products.index') }}">Products</a>
                            </li>
                            <li class="{{ setActive(['admin.color.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.color.index') }}">Color</a></li>
                            <li class="{{ setActive(['admin.size.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.size.index') }}">Size</a></li>
                            <li class="{{ setActive(['admin.product-pending.index']) }}"><a class="nav-link"
                                    href="{{ route('admin.product-pending.index') }}">Pending Product</a></li>
                            <li class="{{ setActive(['admin.product_out_of_stock.index']) }}"><a class="nav-link"
                                    href="{{ route('admin.product_out_of_stock.index') }}">Product Out of Stock</a></li>
                            <li class="{{ setActive(['admin.reviews.index']) }}"><a class="nav-link"
                                    href="{{ route('admin.reviews.index') }}">Product Reviews</a></li>
                        </ul>
                    </li>
                @endcan
                @php
                    $statuses = App\Models\OrderStatus::where('status', 1)->get();
                @endphp
                @can('Manage Orders')
                    <li class="dropdown {{ setActive(['admin.order.*']) }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                class="fas fa-cart-plus"></i></i> <span>Orders</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.order.index']) }}"><a class="nav-link"
                                    href="{{ route('admin.order.index') }}">All Orders </a>
                            </li>
                            @foreach ($statuses as $status)
                                <li class="{{ request()->route('id') == $status->id ? 'active' : '' }}">
                                    <a href="{{ route('admin.order.status', $status->id) }}">{{ $status->name }}</a>
                                </li>
                            @endforeach
                            {{-- @foreach ($statuses as $status)
                        <li>
                            <a class="status-link" href="#" data-status-id="{{ $status->id }}">
                                {{ ucfirst($status->name) }} 
                            </a>
                        </li>
                    @endforeach --}}
                        </ul>
                    </li>
                @endcan
                @can('Manage Ecommerce')
                    <li
                        class="dropdown {{ setActive([
                            'admin.vendor-profile.*',
                            'admin.coupons.*',
                            'admin.shipping-rule.*',
                            'admin.flash-sale.*',
                            'admin.payment-settings.*',
                            'admin.promotions.*',
                            'admin.pickup-shipping.*',
                        ]) }}">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                            <span>E-commerce</span></a>
                        <ul class="dropdown-menu">
                            {{-- <li class="{{ setActive(['admin.flash-sale.*']) }}"><a class="nav-link"
                            href="{{ route('admin.flash-sale.index') }}">Flash Sale</a></li> --}}

                            {{-- global shipping method --}}
                            <li class="{{ setActive(['admin.shipping-rule.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.shipping-rule.index') }}">Shipping Rule</a></li>
                            <li class="{{ setActive(['admin.pickup-shipping.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.pickup-shipping.index') }}">Pickup Shipping </a></li>

                            <li class="{{ setActive(['admin.coupons.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                            <li class="{{ setActive(['admin.campaign.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.campaign.index') }}">Campaign</a></li>
                            <li class="{{ setActive(['admin.promotions.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.promotions.index') }}">Promotions</a></li>
                            <li class="{{ setActive(['admin.payment-settings.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.payment-settings.index') }}">Payment Setting</a></li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Transaction')
                    <li class="dropdown {{ setActive(['admin.transaction', 'admin.mobilepay-transaction']) }} ">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                class="fas fa-money-bill-alt"></i>
                            <span>Transaction</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.transaction']) }}"><a class="nav-link"
                                    href="{{ route('admin.transaction') }}"><i
                                        class="fas fa-money-bill-alt"></i><span>Transactions</span></a>
                            </li>
                            <li class="{{ setActive(['admin.mobilepay-transaction']) }}"><a class="nav-link mt-2"
                                    href="{{ route('admin.mobilepay-transaction') }}"><i
                                        class="fas fa-money-bill-alt"></i><span>MobilePay Transactions</span></a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('Manage Website')
                    <li
                        class="dropdown {{ setActive(['admin.slider.*', 'admin.order-status.*', 'admin.about', 'admin.terms-and-condition', 'admin.create-page.*', 'admin.branch.*']) }} ">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                            <span>Manage Website</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.slider.*']) }}">
                                <a class="nav-link" href="{{ route('admin.slider.index') }}">Slider </a>
                            </li>
                            <li class="{{ setActive(['admin.order-status.*']) }}">
                                <a class="nav-link" href="{{ route('admin.order-status.index') }}">Order Status </a>
                            </li>
                            <li class="{{ setActive(['admin.create-page.*']) }}">
                                <a class="nav-link" href="{{ route('admin.create-page.index') }}">Create Page </a>
                            </li>
                            <li class="{{ setActive(['admin.branch.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.branch.index') }}">Branch</a></li>

                            <li class="{{ setActive(['admin.about']) }}"><a class="nav-link"
                                    href="{{ route('admin.about') }}">About Page</a></li>
                            <li class="{{ setActive(['admin.terms-and-condition']) }}"><a class="nav-link"
                                    href="{{ route('admin.terms-and-condition') }}">Terms and Conditions</a></li>
                        </ul>
                    </li>
                @endcan
                @can('Manage Blog')
                    <li
                        class="dropdown {{ setActive(['admin.blog-category.*', 'admin.blog.*', 'admin.blog-comment.index']) }} ">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                class="fab fa-blogger-b"></i>
                            <span>Manage Blog</span></a>
                        <ul class="dropdown-menu">
                            <li class="{{ setActive(['admin.blog-category.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.blog-category.index') }}">Categories</a></li>
                            <li class="{{ setActive(['admin.blog.*']) }}"><a class="nav-link"
                                    href="{{ route('admin.blog.index') }}">Blogs</a></li>
                            {{-- <li class="{{ setActive(['admin.blog-comment.index']) }}"><a class="nav-link"
                            href="{{ route('admin.blog-comment.index') }}">Blogs Comments</a></li> --}}
                        </ul>
                    </li>
                @endcan
            @endcanany
            @can('Administration')
                <li class="menu-header">Authorization</li>
                <li class="{{ setActive(['admin.permission.*']) }}"><a class="nav-link"
                        href="{{ route('admin.permission.index') }}"><i class="fab fa-accessible-icon"></i><span>Permission</span></a>
                </li>
                <li class="{{ setActive(['admin.role.*']) }}"><a class="nav-link"
                        href="{{ route('admin.role.index') }}"><i class="fas fa-user-shield"></i><span>Roles</span></a>
                </li>
                <li class="{{ setActive(['admin.users.*']) }}"><a class="nav-link"
                        href="{{ route('admin.users.index') }}"><i class="far fa-user"></i><span>Users</span></a>
                </li>
            @endcan
            @can('Manage Employee')
                <li class="menu-header">Manage Employee</li>
                <li class="{{ setActive(['admin.employees.*']) }}"><a class="nav-link"
                        href="{{ route('admin.employees.index') }}"><i class="far fa-user"></i><span>Employee
                            List</span></a>
                </li>
            @endcan
            @php
                $employee = Auth::user()->employee;
            @endphp

            @if ($employee && $employee->role_name == 'employee')
                <li class="menu-header">Employee</li>
                <li class="{{ setActive(['employee.attendance.*']) }}"><a class="nav-link"
                        href="{{ route('employee.attendance.index') }}"><i class="far fa-user"></i><span>My
                            Attendance</span></a>
                </li>
            @endif
            @can('Manage Setting & More')
                <li class="menu-header">Settings & More</li>
                <li
                    class="dropdown {{ setActive([
                        'admin.footer-info.*',
                        'admin.footer-socials.*',
                        // 'admin.footer-grid-two.*',
                        // 'admin.footer-grid-three.*',
                    ]) }} ">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                            class="fas fa-th-large"></i> <span>Footer</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ setActive(['admin.footer-info.*']) }}"><a class="nav-link"
                                href="{{ route('admin.footer-info.index') }}">Footer Info </a></li>
                        <li class="{{ setActive(['admin.footer-socials.*']) }}"><a class="nav-link"
                                href="{{ route('admin.footer-socials.index') }}">Footer Social </a></li>
                        {{-- <li class="{{ setActive(['admin.footer-grid-two.*']) }}"><a class="nav-link"
                            href="{{ route('admin.footer-grid-two.index') }}">Footer Grid Two </a></li>
                    <li class="{{ setActive(['admin.footer-grid-three.*']) }}"><a class="nav-link"
                            href="{{ route('admin.footer-grid-three.index') }}">Footer Grid Three </a></li> --}}
                    </ul>
                </li>
                <li
                    class="dropdown {{ setActive([
                        'admin.customer.index',
                        // 'admin.manage-user.index',
                        'admin.admin_list.index',
                    ]) }} ">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i>
                        <span>Users</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
                                href="{{ route('admin.customer.index') }}">Customer list</a></li>
                        {{-- <li class="{{ setActive(['admin.vendor.index']) }}"><a class="nav-link"
                            href="{{ route('admin.vendor.index') }}">Vendor list</a></li> --}}
                        {{-- <li class="{{ setActive(['admin.admin_list.index']) }}"><a class="nav-link"
                                href="{{ route('admin.admin_list.index') }}">Admin list</a></li> --}}
                        {{-- <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Manager User </a></li> --}}
                    </ul>
                </li>

                <li class="{{ setActive(['admin.setting.index']) }}"><a class="nav-link"
                        href="{{ route('admin.setting.index') }}"><i class="fas fa-wrench"></i><span>Settings</span></a>
                </li>
            @endcan
            @can('Manage Job Application')     
            <li class="menu-header">Job Application</li>
            <li class="{{ setActive(['admin.job-application.*']) }}"><a class="nav-link"
                    href="{{ route('admin.job-application.index') }}"><i class="fas fa-file"></i><span>All Application</span></a>
            </li>
            @endcan

            {{-- <li class="dropdown {{ setActive([
            'admin.shipping-methods.*', 
            'admin.countries.*', 
            'admin.states.*',
            ]) }} ">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i>
                    <span>Shipping Setup</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.shipping-methods.*']) }}">
                        <a class="nav-link" href="{{ route('admin.shipping-methods.index') }}">Shipping Methods</a>
                    </li>
                    <li class="{{ setActive(['admin.countries.*']) }}">
                        <a class="nav-link" href="{{ route('admin.countries.index') }}">Countries</a>
                    </li>
                    <li class="{{ setActive(['admin.states.*']) }}">
                        <a class="nav-link" href="{{ route('admin.states.index') }}">States / Zones</a>
                    </li>
                </ul>
            </li> --}}

        </ul>
    </aside>
</div>
