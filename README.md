# Amazon E-commerce API Documentation

## 📋 Table of Contents
- [Introduction](#introduction)
- [Authentication System](#authentication-system)
- [API Endpoints](#api-endpoints)
- [Installation](#installation)
- [Error Codes](#error-codes)

---

## Introduction

This is a complete e-commerce RESTful API built with Laravel.

### User Roles
- **Admin** - Full access to manage products, categories, and users
- **Customer** - Browse products, manage cart, place orders
- **Delivery** - Update delivery status

**Base URL:** `http://localhost:8000/api`

---

## Authentication System

### Authentication Flow
Register → 2. Verify OTP → 3. Login → 4. Use Bearer Token

text

### Password Reset Flow
Forget Password → 2. Receive OTP → 3. Reset Password

text

### Common Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {},
    "token": "optional_token"
}
Error Response:

json
{
    "success": false,
    "message": "Error description",
    "errors": {}
}
API Endpoints
🔐 Authentication (Customer)
Method	Endpoint	Description
POST	/customer/register	Register new customer
POST	/customer/login	Login customer
POST	/customer/verify-otp	Verify email OTP
POST	/customer/resend-otp	Resend verification OTP
POST	/customer/forget	Request password reset
POST	/customer/reset	Reset password with OTP
POST	/customer/logout	Logout (requires auth)
GET	/customer/me	Get profile (requires auth)
1. Customer Register
Endpoint: POST /api/customer/register

Request:

json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "01012345678",
    "gender": "male"
}
Response (201 Created):

json
{
    "success": true,
    "message": "User registered",
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "type": "customer",
        "status": "active"
    }
}
Note: OTP sent to email. Verify before login.

2. Customer Login
Endpoint: POST /api/customer/login

Request:

json
{
    "email": "john@example.com",
    "password": "password123"
}
Response (200 OK):

json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz123456"
    }
}
3. Verify OTP
Endpoint: POST /api/customer/verify-otp

Request:

json
{
    "email": "john@example.com",
    "otp": "123456"
}
Response:

json
{
    "message": "Email verified successfully",
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
}
4. Resend OTP
Endpoint: POST /api/customer/resend-otp

Request:

json
{
    "email": "john@example.com"
}
Response:

json
{
    "message": "OTP resent successfully"
}
5. Forget Password
Endpoint: POST /api/customer/forget

Request:

json
{
    "email": "john@example.com"
}
Response:

json
{
    "message": "OTP sent to your email"
}
6. Reset Password
Endpoint: POST /api/customer/reset

Request:

json
{
    "email": "john@example.com",
    "otp": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
Response:

json
{
    "message": "Password reset successfully"
}
🔐 Authentication (Admin)
Method	Endpoint	Description
POST	/admin/register	Register new admin
POST	/admin/login	Login admin
POST	/admin/logout	Logout
GET	/admin/me	Get profile
GET	/admin/allUsers	Get all users
Admin Register Request:

json
{
    "first_name": "Admin",
    "last_name": "User",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
👤 Profile Management
All endpoints require authentication token.

Method	Endpoint	Description
GET	/customer/profile	Get profile
POST	/customer/profile	Update profile
POST	/customer/profile/change-password	Change password
DELETE	/customer/profile/avatar	Delete avatar
DELETE	/customer/profile/account	Delete account
Update Profile (multipart/form-data):

Key	Value	Type
first_name	John	Text
last_name	Doe	Text
phone	01012345678	Text
gender	male	Text
avatar	image.jpg	File
Change Password:

json
{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
📦 Products
Method	Endpoint	Auth
GET	/products	Public
GET	/products/{id}	Public
GET	/products/filter	Public
POST	/products	Admin
PUT/PATCH	/products/{id}	Admin
DELETE	/products/{id}	Admin
POST	/products/{id}/restore	Admin
DELETE	/products/{id}/force	Admin
List Products (Public):

text
GET /api/products?category_id=1
Response:

json
[
    {
        "id": 1,
        "name": "iPhone 14 Pro",
        "price": "999.00",
        "stock": 50,
        "image": "http://localhost:8000/storage/products/iphone.jpg",
        "categories": [
            {
                "id": 1,
                "name": "Electronics"
            }
        ]
    }
]
Filter Products:

text
GET /api/products/filter?price_min=100&price_max=500&name=phone
Create Product (Admin - multipart/form-data):

Key	Required
name	Yes
price	Yes
stock	Yes
sku	Yes
description	No
image	No
categories	No
📁 Categories
Method	Endpoint	Auth
GET	/categories	Public
GET	/categories/{id}	Public
GET	/categories/{id}/products	Public
POST	/categories	Admin
PUT/PATCH	/categories/{id}	Admin
DELETE	/categories/{id}	Admin
Create Category (Admin):

json
{
    "name": "Electronics",
    "parent_id": null
}
🛒 Cart
All cart endpoints require authentication.

Method	Endpoint	Description
GET	/carts	Get cart
POST	/carts	Add item
PATCH	/carts/{id}	Update quantity
DELETE	/carts/{id}	Remove item
Add to Cart:

json
{
    "product_id": 1,
    "quantity": 2
}
Get Cart Response:

json
{
    "success": true,
    "cart": [
        {
            "id": 1,
            "product_id": 1,
            "quantity": 2,
            "subtotal": "1998.00",
            "product": {
                "name": "iPhone 14 Pro",
                "price": "999.00"
            }
        }
    ],
    "total": 1998.00
}
📦 Orders & Checkout
Method	Endpoint	Description
POST	/checkout	Place order
GET	/orders	Order history
GET	/orders/{id}	Order details
Checkout Request:

json
{
    "shipping_name": "John Doe",
    "shipping_address": "123 Main Street",
    "shipping_city": "Cairo",
    "shipping_zipcode": "12345",
    "shipping_country": "Egypt",
    "shipping_phone": "01012345678",
    "payment_method": "credit_card",
    "notes": "Leave at door"
}
💳 Payments
Method	Endpoint	Auth
POST	/orders/{id}/payments	Yes
GET	/payments/{id}/confirm	Yes
POST	/webhooks/stripe	No
Create Payment:

json
{
    "provider": "stripe"
}
Response:

json
{
    "success": true,
    "client_secret": "pi_xxx_secret_xxx",
    "payment_id": 1,
    "publishable_key": "pk_test_xxx"
}
🔑 Authentication Header
For protected routes:

text
Authorization: Bearer {your_token_here}
📊 HTTP Status Codes
Code	Description
200	OK - Successful
201	Created - Resource created
400	Bad Request
401	Unauthorized
403	Forbidden
404	Not Found
422	Validation failed
500	Server Error
🚀 Installation
bash
# Clone repository
git clone https://github.com/marwan2096/amazon-api.git

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed --class=RolesAndPermissionsSeeder

# Create storage link
php artisan storage:link

# Start server
php artisan serve
Required .env Variables
env
APP_URL=http://localhost:8000

DB_DATABASE=amazon
DB_USERNAME=root
DB_PASSWORD=

MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password

STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
🔒 Permissions & Roles
Role	Permissions
Admin	All permissions
Customer	view products, view orders, create orders
Delivery	view deliveries, update delivery status
📧 OTP & Email
OTP is 6 digits

Sent to registered email

Required for verification and password reset
