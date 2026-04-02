<?php

return [
    'routes' => [
        // Dashboard
        'admin.dashboard' => 'Dashboard',

        // Categories
        'admin.category.index' => 'Category List',
        'admin.category.create' => 'Create Category',
        'admin.sub-category.index' => 'Sub Category List',
        'admin.sub-category.create' => 'Create Sub Category',
        'admin.child-category.index' => 'Child Category List',
        'admin.child-category.create' => 'Create Child Category',

        // Products
        'admin.brand.index' => 'Brand List',
        'admin.brand.create' => 'Create Brand',
        'admin.products.index' => 'Product List',
        'admin.products.create' => 'Create Product',
        'admin.size.index' => 'Size List',
        'admin.size.create' => 'Create Size',
        'admin.color.index' => 'Color List',
        'admin.color.create' => 'Create Color',
        'admin.product-pending.index' => 'Pending Products',
        'admin.product_out_of_stock.index' => 'Out of Stock Products',
        'admin.reviews.index' => 'Product Reviews',

        // Orders
        'admin.order.index' => 'Orders',

        // Ecommerce
        'admin.shipping-rule.index' => 'Shipping Rules',
        'admin.shipping-rule.create' => 'Create Shipping Rule',
        'admin.pickup-shipping.index' => 'Pickup Shipping',
        'admin.pickup-shipping.create' => 'Create Pickup Shipping',
        'admin.coupons.index' => 'Coupons',
        'admin.coupons.create' => 'Create Coupon',
        'admin.campaign.index' => 'Campaigns',
        'admin.campaign.create' => 'Create Campaign',
        'admin.promotions.index' => 'Promotions',
        'admin.promotions.create' => 'Create Promotion',
        'admin.payment-settings.index' => 'Payment Settings',

        // Integrations
        'admin.integrations.courier' => 'Courier API',
        'admin.integrations.sms' => 'SMS API',
        'admin.integrations.ai' => 'AI API',

        // Blog
        'admin.blog-category.index' => 'Blog Categories',
        'admin.blog-category.create' => 'Create Blog Category',
        'admin.blog.index' => 'Blogs',
        'admin.blog.create' => 'Create Blog',

        // Website
        'admin.slider.index' => 'Sliders',
        'admin.slider.create' => 'Create Slider',
        'admin.branch.index' => 'Branches',
        'admin.branch.create' => 'Create Branch',
        'admin.about' => 'About Page',
        'admin.terms-and-condition' => 'Terms & Conditions',
        'admin.create-page.index' => 'Pages',
        'admin.create-page.create' => 'Create Page',

        // Users & Authorization
        'admin.users.index' => 'Users',
        'admin.users.create' => 'Create User',
        'admin.role.index' => 'Roles',
        'admin.role.create' => 'Create Role',
        'admin.permission.index' => 'Permissions',
        'admin.permission.create' => 'Create Permission',
        'admin.employees.index' => 'Employees',
        'admin.employees.create' => 'Create Employee',
        'admin.customer.index' => 'Customer List',
        'admin.admin_list.index' => 'Admin List',

        // Settings
        'admin.footer-info.index' => 'Footer Info',
        'admin.footer-socials.index' => 'Footer Socials',
        'admin.setting.index' => 'Settings',

        // Transactions
        'admin.transaction' => 'Transactions',
        'admin.mobilepay-transaction' => 'MobilePay Transactions',
    ],
];
