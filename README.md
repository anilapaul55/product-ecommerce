# Laravel E-commerce Project

This is a Laravel-based E-commerce project featuring user login, registration, product management, and a session-based cart system.

# Features

User Authentication: Registration and login for users and admin.

CRUD Operations: Create, Read, Update, Delete functionality for products.

Excel Import: Upload products in bulk using Excel. Excel format:
                name, category, color, size, qty, price, image


Image Conversion: Automatically converts JPEG & JPG images to WebP format for optimized performance.

# Cart System (Session-Based):

        Add to Cart
        
        Apply Coupon (WELCOME100)
        
        Checkout functionality

# Database Setup

The database is created using Laravel migrations.

# Run migrations with:

        php artisan migrate

# Seeders

Seeders have been created for initial data population:

    1. CouponSeeder
    2. UserSeeder
    3. CategorySeeder
    4. ColorSeeder
    5. SizeSeeder

# Run all seeders with:
    php artisan db:seed

# Default Credentials
1. Admin:
    username: admin@example.com
    Password: admin123
2. User:
    username: user@example.com
    Password: user123

# Usage
 Clone the repository

# Install dependencies:

    composer install
    npm install
    npm run dev


Set up .env file with database credentials
Run migrations and seeders

# Start the development server:

php artisan serve
