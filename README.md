Amazon E-commerce API Documentation
📋 Table of Contents
Introduction

Authentication System

API Endpoints

Authentication

Profile Management

Products

Categories

Cart

Orders & Checkout

Payments

Error Codes

Introduction
This is a complete e-commerce RESTful API built with Laravel. The API supports three user roles:

Admin - Full access to manage products, categories, and users

Customer - Can browse products, manage cart, place orders

Delivery - Can update delivery status

Base URL: http://localhost:8000/api

Authentication System
Authentication Flow
text
1. Register → 2. Verify OTP → 3. Login → 4. Use Bearer Token
Password Reset Flow
text
1. Forget Password → 2. Receive OTP → 3. Reset Password
Common Response Format
All endpoints return responses in this format:

json
{
    "success": true,
    "message": "Operation successful",
    "data": {},
    "token": "optional_token"
}
Error response:

json
{
    "success": false,
    "message": "Error description",
    "errors": {}
}
API Endpoints
🔐 Authentication
Customer Routes (/api/customer)
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

Request Body (JSON):

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
Field	Type	Required	Description
first_name	string	Yes	Max 50 chars
last_name	string	Yes	Max 50 chars
email	string	Yes	Valid email, unique
password	string	Yes	Min 8 chars
password_confirmation	string	Yes	Must match password
phone	string	No	10-20 digits, unique
gender	string	No	Max 20 chars
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
        "phone": "01012345678",
        "gender": "male",
        "type": "customer",
        "status": "active",
        "email_verified_at": null,
        "created_at": "2024-01-01T10:00:00.000000Z"
    }
}
Note: An OTP will be sent to the registered email. User must verify before login.

2. Customer Login
Endpoint: POST /api/customer/login

Request Body:

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
            "email": "john@example.com",
            "type": "customer"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz123456"
    }
}
Error Responses:

401 - Invalid credentials

403 - Email not verified

3. Verify OTP
Endpoint: POST /api/customer/verify-otp

Request Body:

json
{
    "email": "john@example.com",
    "otp": "123456"
}
Response (200 OK):

json
{
    "message": "Email verified successfully",
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
}
4. Resend OTP
Endpoint: POST /api/customer/resend-otp

Request Body:

json
{
    "email": "john@example.com"
}
Response (200 OK):

json
{
    "message": "OTP resent successfully"
}
5. Forget Password
Endpoint: POST /api/customer/forget

Request Body:

json
{
    "email": "john@example.com"
}
Response (200 OK):

json
{
    "message": "OTP sent to your email"
}
6. Reset Password
Endpoint: POST /api/customer/reset

Request Body:

json
{
    "email": "john@example.com",
    "otp": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
Response (200 OK):

json
{
    "message": "Password reset successfully"
}
7. Customer Logout
Endpoint: POST /api/customer/logout

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "success": true,
    "message": "Logged out"
}
8. Get Current User (Me)
Endpoint: GET /api/customer/me

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "success": true,
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "01012345678",
        "gender": "male",
        "type": "customer",
        "avatar": "http://localhost:8000/storage/avatars/avatar.jpg"
    }
}
Admin Routes (/api/admin)
Method	Endpoint	Description
POST	/admin/register	Register new admin
POST	/admin/login	Login admin
POST	/admin/logout	Logout (requires auth)
GET	/admin/me	Get profile (requires auth)
GET	/admin/allUsers	Get all users (requires auth)
Admin Register Request:

json
{
    "first_name": "Admin",
    "last_name": "User",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
Admin Login Response:

json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "first_name": "Admin",
            "last_name": "User",
            "email": "admin@example.com",
            "type": "admin"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz123456"
    }
}
👤 Profile Management (/api/customer/profile)
All profile endpoints require authentication.

Method	Endpoint	Description
GET	/customer/profile	Get profile
POST	/customer/profile	Update profile
POST	/customer/profile/change-password	Change password
DELETE	/customer/profile/avatar	Delete avatar
DELETE	/customer/profile/account	Delete account
Update Profile
Endpoint: POST /api/customer/profile

Headers:

text
Authorization: Bearer {token}
Content-Type: multipart/form-data
Request Body (form-data):

Key	Value	Type
first_name	John	Text
last_name	Doe	Text
phone	01012345678	Text
gender	male	Text
birth_date	1990-01-01	Text
avatar	image.jpg	File
Response (200 OK):

json
{
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "avatar": "http://localhost:8000/storage/avatars/avatar.jpg"
    },
    "message": "profile updated"
}
Change Password
Endpoint: POST /api/customer/profile/change-password

Request Body:

json
{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
Response (200 OK):

json
{
    "message": "password changed"
}
Error (401):

json
{
    "message": "wrong password"
}
📦 Products
Method	Endpoint	Description	Auth
GET	/products	List all products	No
GET	/products/{id}	Get single product	No
GET	/products/filter	Filter products	No
POST	/products	Create product	Admin only
PUT/PATCH	/products/{id}	Update product	Admin only
DELETE	/products/{id}	Soft delete product	Admin only
POST	/products/{id}/restore	Restore product	Admin only
DELETE	/products/{id}/force	Force delete	Admin only
GET	/admin/products	Get all (including deleted)	Admin only
List All Products (Public)
Endpoint: GET /api/products

Query Parameters (optional):

Parameter	Type	Description
category_id	integer	Filter by category ID
Response (200 OK):

json
[
    {
        "id": 1,
        "name": "iPhone 14 Pro",
        "slug": "iphone-14-pro",
        "description": "Latest Apple smartphone",
        "price": "999.00",
        "stock": 50,
        "sku": "IP14P-001",
        "is_active": true,
        "image": "http://localhost:8000/storage/products/iphone14.jpg",
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z",
        "categories": [
            {
                "id": 1,
                "name": "Electronics",
                "slug": "electronics"
            }
        ]
    }
]
Filter Products
Endpoint: GET /api/products/filter

Query Parameters:

Parameter	Type	Description
price_min	numeric	Minimum price
price_max	numeric	Maximum price
stock_min	integer	Minimum stock
name	string	Search by name (partial match)
Example Request:

text
GET /api/products/filter?price_min=100&price_max=500&name=phone
Response (200 OK):

json
{
    "success": true,
    "message": "Products retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "iPhone 14 Pro",
            "price": "999.00",
            "stock": 50
        }
    ]
}
Get Single Product
Endpoint: GET /api/products/{id}

Response (200 OK):

json
{
    "id": 1,
    "name": "iPhone 14 Pro",
    "slug": "iphone-14-pro",
    "description": "Latest Apple smartphone with A16 Bionic chip",
    "price": "999.00",
    "stock": 50,
    "sku": "IP14P-001",
    "is_active": true,
    "image": "http://localhost:8000/storage/products/iphone14.jpg",
    "created_at": "2024-01-01T10:00:00.000000Z",
    "updated_at": "2024-01-01T10:00:00.000000Z",
    "categories": [
        {
            "id": 1,
            "name": "Electronics"
        }
    ]
}
Create Product (Admin Only)
Endpoint: POST /api/products

Headers:

text
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data
Request Body (form-data):

Key	Value	Type	Required
name	iPhone 14 Pro	Text	Yes
price	999	Text	Yes
stock	50	Text	Yes
sku	IP14P-001	Text	Yes
description	Latest smartphone	Text	No
is_active	1	Text	No
categories	1,2,3	Text	No
image	iphone.jpg	File	No
Response (201 Created):

json
{
    "success": true,
    "message": "Product created",
    "data": {
        "id": 1,
        "name": "iPhone 14 Pro",
        "slug": "iphone-14-pro",
        "price": "999.00",
        "stock": 50,
        "sku": "IP14P-001",
        "image": "http://localhost:8000/storage/products/iphone14.jpg",
        "categories": []
    }
}
Update Product (Admin Only)
Endpoint: PATCH /api/products/{id}

Headers:

text
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data
Request Body (form-data) - all fields optional:

Key	Value	Type
name	iPhone 15 Pro	Text
price	1099	Text
stock	45	Text
sku	IP15P-001	Text
is_active	1	Text
categories	1,2	Text
image	new-image.jpg	File
Response (200 OK):

json
{
    "id": 1,
    "name": "iPhone 15 Pro",
    "slug": "iphone-15-pro",
    "price": "1099.00",
    "stock": 45,
    "sku": "IP15P-001",
    "is_active": true,
    "image": "http://localhost:8000/storage/products/new-image.jpg",
    "categories": [
        {"id": 1, "name": "Electronics"}
    ]
}
Delete Product (Soft Delete - Admin Only)
Endpoint: DELETE /api/products/{id}

Headers:

text
Authorization: Bearer {admin_token}
Response (200 OK):

json
{
    "message": "Product deleted successfully"
}
Restore Product (Admin Only)
Endpoint: POST /api/products/{id}/restore

Headers:

text
Authorization: Bearer {admin_token}
Response (200 OK):

json
{
    "success": true,
    "message": "Product restored successfully"
}
Force Delete Product (Admin Only)
Endpoint: DELETE /api/products/{id}/force

Headers:

text
Authorization: Bearer {admin_token}
Response (200 OK):

json
{
    "success": true,
    "message": "Product permanently deleted successfully"
}
📁 Categories
Method	Endpoint	Description	Auth
GET	/categories	List all categories	No
GET	/categories/{id}	Get single category	No
GET	/categories/{id}/products	Get products in category	No
POST	/categories	Create category	Admin only
PUT/PATCH	/categories/{id}	Update category	Admin only
DELETE	/categories/{id}	Delete category	Admin only
List All Categories
Endpoint: GET /api/categories

Response (200 OK):

json
[
    {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics",
        "is_active": true,
        "parent_id": null,
        "parent": null,
        "created_at": "2024-01-01T10:00:00.000000Z",
        "updated_at": "2024-01-01T10:00:00.000000Z"
    },
    {
        "id": 2,
        "name": "Smartphones",
        "slug": "smartphones",
        "is_active": true,
        "parent_id": 1,
        "parent": {
            "id": 1,
            "name": "Electronics"
        }
    }
]
Get Category with Products
Endpoint: GET /api/categories/{id}/products

Response (200 OK):

json
{
    "message": "successfully",
    "data": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics",
        "products": [
            {
                "id": 1,
                "name": "iPhone 14 Pro",
                "price": "999.00",
                "image": "http://localhost:8000/storage/products/iphone.jpg"
            }
        ]
    }
}
Create Category (Admin Only)
Endpoint: POST /api/categories

Headers:

text
Authorization: Bearer {admin_token}
Content-Type: application/json
Request Body:

json
{
    "name": "Electronics",
    "parent_id": null
}
Response (201 Created):

json
{
    "message": "Category created successfully",
    "category": {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics",
        "parent_id": null,
        "is_active": true,
        "parent": null
    }
}
Update Category (Admin Only)
Endpoint: PUT /api/categories/{id}

Request Body:

json
{
    "name": "Electronics & Gadgets",
    "is_active": true,
    "parent_id": null
}
Response (200 OK):

json
{
    "id": 1,
    "name": "Electronics & Gadgets",
    "slug": "electronics-gadgets",
    "is_active": true,
    "parent_id": null
}
Delete Category (Admin Only)
Endpoint: DELETE /api/categories/{id}

Headers:

text
Authorization: Bearer {admin_token}
Response (200 OK):

json
{
    "message": "Category deleted successfully"
}
Note: Children categories will be reassigned to parent category.

🛒 Cart
All cart endpoints require authentication.

Method	Endpoint	Description
GET	/carts	Get user's cart
POST	/carts	Add item to cart
GET	/carts/{id}	Get specific cart item
PUT/PATCH	/carts/{id}	Update quantity
DELETE	/carts/{id}	Remove item from cart
Get Cart
Endpoint: GET /api/carts

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "success": true,
    "cart": [
        {
            "id": 1,
            "product_id": 1,
            "user_id": 1,
            "quantity": 2,
            "subtotal": "1998.00",
            "product": {
                "id": 1,
                "name": "iPhone 14 Pro",
                "price": "999.00",
                "image": "http://localhost:8000/storage/products/iphone.jpg"
            }
        }
    ],
    "total": 1998.00
}
Add to Cart
Endpoint: POST /api/carts

Headers:

text
Authorization: Bearer {token}
Content-Type: application/json
Request Body:

json
{
    "product_id": 1,
    "quantity": 2
}
Response (201 Created):

json
{
    "success": true,
    "message": "Product added to cart",
    "cart_item": {
        "id": 1,
        "product_id": 1,
        "user_id": 1,
        "quantity": 2
    }
}
Note: If product already exists in cart, quantity will be increased.

Update Cart Item Quantity
Endpoint: PATCH /api/carts/{id}

Headers:

text
Authorization: Bearer {token}
Content-Type: application/json
Request Body:

json
{
    "quantity": 3
}
Response (200 OK):

json
{
    "success": true,
    "message": "Cart updated",
    "cart_item": {
        "id": 1,
        "product_id": 1,
        "quantity": 3,
        "subtotal": "2997.00",
        "product": {
            "name": "iPhone 14 Pro",
            "price": "999.00"
        }
    }
}
Remove from Cart
Endpoint: DELETE /api/carts/{id}

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "success": true,
    "message": "Item removed from cart"
}
📦 Orders & Checkout
All order endpoints require authentication.

Method	Endpoint	Description
POST	/checkout	Place order
GET	/orders	Get order history
GET	/orders/{id}	Get order details
Checkout (Place Order)
Endpoint: POST /api/checkout

Headers:

text
Authorization: Bearer {token}
Content-Type: application/json
Request Body:

json
{
    "shipping_name": "John Doe",
    "shipping_address": "123 Main Street",
    "shipping_city": "Cairo",
    "shipping_state": "Cairo Governorate",
    "shipping_zipcode": "12345",
    "shipping_country": "Egypt",
    "shipping_phone": "01012345678",
    "payment_method": "credit_card",
    "notes": "Leave at door"
}
Field	Required	Description
shipping_name	Yes	Full name for shipping
shipping_address	Yes	Street address
shipping_city	Yes	City name
shipping_state	No	State/Province
shipping_zipcode	Yes	Postal code
shipping_country	Yes	Country name
shipping_phone	Yes	Contact phone
payment_method	Yes	credit_card or paypal
notes	No	Order notes
Response (201 Created):

json
{
    "message": "Order placed successfully",
    "order": {
        "id": 1,
        "order_number": "ORD-20240101-0001",
        "status": "pending",
        "subtotal": "1998.00",
        "tax": "159.84",
        "shipping_cost": "5.00",
        "total": "2162.84",
        "payment_method": "credit_card",
        "payment_status": "pending",
        "shipping_name": "John Doe",
        "shipping_address": "123 Main Street",
        "shipping_city": "Cairo",
        "shipping_zipcode": "12345",
        "shipping_country": "Egypt",
        "shipping_phone": "01012345678",
        "notes": "Leave at door",
        "items": [
            {
                "product_id": 1,
                "product_name": "iPhone 14 Pro",
                "quantity": 2,
                "price": "999.00",
                "subtotal": "1998.00"
            }
        ]
    }
}
Order History
Endpoint: GET /api/orders

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "message": "order done",
    "orders": [
        {
            "id": 1,
            "order_number": "ORD-20240101-0001",
            "status": "delivered",
            "total": "2162.84",
            "created_at": "2024-01-01T10:00:00.000000Z",
            "items": [...]
        }
    ]
}
Order Details
Endpoint: GET /api/orders/{id}

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "message": "order done",
    "order": {
        "id": 1,
        "order_number": "ORD-20240101-0001",
        "status": "pending",
        "subtotal": "1998.00",
        "tax": "159.84",
        "shipping_cost": "5.00",
        "total": "2162.84",
        "payment_method": "credit_card",
        "payment_status": "pending",
        "shipping_name": "John Doe",
        "shipping_address": "123 Main Street",
        "shipping_city": "Cairo",
        "shipping_state": null,
        "shipping_zipcode": "12345",
        "shipping_country": "Egypt",
        "shipping_phone": "01012345678",
        "notes": "Leave at door",
        "created_at": "2024-01-01T10:00:00.000000Z",
        "items": [
            {
                "id": 1,
                "product_id": 1,
                "product_name": "iPhone 14 Pro",
                "product_sku": "IP14P-001",
                "quantity": 2,
                "price": "999.00",
                "subtotal": "1998.00"
            }
        ]
    }
}
💳 Payments
Method	Endpoint	Description	Auth
POST	/orders/{id}/payments	Create payment	Yes
GET	/payments/{id}/confirm	Confirm payment	Yes
POST	/webhooks/stripe	Stripe webhook	No
Create Payment (Stripe)
Endpoint: POST /api/orders/{orderId}/payments

Headers:

text
Authorization: Bearer {token}
Content-Type: application/json
Request Body:

json
{
    "provider": "stripe"
}
Response (200 OK):

json
{
    "success": true,
    "client_secret": "pi_3xxxxxxxxx_secret_xxxxxxxxx",
    "payment_id": 1,
    "publishable_key": "pk_test_xxxxxxxxx"
}
Note: Frontend uses client_secret to confirm payment with Stripe.js

Confirm Payment Status
Endpoint: GET /api/payments/{paymentId}/confirm

Headers:

text
Authorization: Bearer {token}
Response (200 OK):

json
{
    "success": true,
    "message": "Payment confirmed successfully",
    "payment": {
        "id": 1,
        "order_id": 1,
        "amount": "2162.84",
        "currency": "usd",
        "status": "completed",
        "provider": "stripe"
    },
    "order": {
        "id": 1,
        "order_number": "ORD-20240101-0001",
        "payment_status": "paid",
        "status": "processing"
    }
}
🚚 Delivery Routes (Similar to Customer)
Delivery users have their own set of routes under /api/delivery:

Method	Endpoint	Description
POST	/delivery/register	Register delivery
POST	/delivery/login	Login delivery
POST	/delivery/verify-otp	Verify OTP
POST	/delivery/forget	Forget password
POST	/delivery/reset	Reset password
POST	/delivery/logout	Logout
GET	/delivery/me	Get profile
🔑 Authentication Header
For all protected routes, include the token in the Authorization header:

text
Authorization: Bearer {your_token_here}
Example:

text
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
📊 HTTP Status Codes
Code	Description
200	OK - Request successful
201	Created - Resource created
400	Bad Request - Invalid input
401	Unauthorized - Authentication required
403	Forbidden - Insufficient permissions
404	Not Found - Resource not found
422	Unprocessable Entity - Validation failed
500	Internal Server Error
🚀 Installation & Setup
Requirements
PHP >= 8.1

MySQL >= 5.7

Composer

Redis (optional, for caching)

Steps
bash
# Clone repository
git clone https://github.com/yourusername/amazon-api.git

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amazon
DB_USERNAME=root
DB_PASSWORD=

# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed --class=RolesAndPermissionsSeeder

# Create storage link
php artisan storage:link

# Start server
php artisan serve
Environment Variables (.env)
env
# Application
APP_NAME="Amazon API"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amazon
DB_USERNAME=root
DB_PASSWORD=

# Mail (for OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Amazon API"

# Stripe (for payments)
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
📝 Postman Collection
You can import the following collection into Postman:

json
{
  "info": {
    "name": "Amazon E-commerce API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Customer Register",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/customer/register",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "body": {
              "mode": "raw",
              "raw": "{\n    \"first_name\": \"John\",\n    \"last_name\": \"Doe\",\n    \"email\": \"john@example.com\",\n    \"password\": \"password123\",\n    \"password_confirmation\": \"password123\"\n}"
            }
          }
        },
        {
          "name": "Customer Login",
          "request": {
            "method": "POST",
            "url": "{{base_url}}/customer/login",
            "header": [{"key": "Content-Type", "value": "application/json"}],
            "body": {
              "mode": "raw",
              "raw": "{\n    \"email\": \"john@example.com\",\n    \"password\": \"password123\"\n}"
            }
          }
        }
      ]
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000/api"
    }
  ]
}
📧 OTP & Email Notifications
The system sends email notifications for:

Email Verification - When a new user registers

Login Notification - When a user logs in

Verified Notification - When email is verified

Password Reset - When user requests password reset

OTP is 6 digits and expires after a certain time.

🔒 Permissions & Roles
Role	Permissions
Admin	All permissions
Customer	view products, view orders, create orders, cancel orders
Delivery	view deliveries, update delivery status, view orders, view products
📞 Support
For issues or questions:

Email: support@amazon-api.com

GitHub Issues: Create an issue
