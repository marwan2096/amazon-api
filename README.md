📘 Amazon E-commerce API Documentation
📑 Table of Contents
Introduction
🔐 Authentication System
📡 API Endpoints
👤 Profile Management
📦 Products
📁 Categories
🛒 Cart
📦 Orders & Checkout
💳 Payments
🔑 Authentication Header
📊 HTTP Status Codes
🚀 Installation
🔒 Roles & Permissions
📧 OTP & Email
1. Introduction

A complete RESTful E-commerce API built with Laravel.

👥 User Roles
Role	Description
Admin	Full control (products, categories, users)
Customer	Browse, cart, orders
Delivery	Manage delivery status

Base URL

http://localhost:8000/api
🔐 2. Authentication System
🔄 Authentication Flow
Register → Verify OTP → Login → Use Bearer Token
🔁 Password Reset Flow
Forget Password → Receive OTP → Reset Password
📦 Common Response Format
✅ Success Response
{
  "success": true,
  "message": "Operation successful",
  "data": {},
  "token": "optional_token"
}
❌ Error Response
{
  "success": false,
  "message": "Error description",
  "errors": {}
}
📡 3. API Endpoints
🔐 Authentication (Customer)
Method	Endpoint	Description
POST	/customer/register	Register
POST	/customer/login	Login
POST	/customer/verify-otp	Verify OTP
POST	/customer/resend-otp	Resend OTP
POST	/customer/forget	Forget password
POST	/customer/reset	Reset password
POST	/customer/logout	Logout
GET	/customer/me	Current user
3.1 Register

Endpoint

POST /api/customer/register
Request
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "01012345678",
  "gender": "male"
}
Response
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

📌 Note: OTP is sent to email before login.

3.2 Login
POST /api/customer/login
{
  "email": "john@example.com",
  "password": "password123"
}
3.3 Verify OTP
POST /api/customer/verify-otp
{
  "email": "john@example.com",
  "otp": "123456"
}
🔐 Authentication (Admin)
Method	Endpoint
POST	/admin/register
POST	/admin/login
POST	/admin/logout
GET	/admin/me
GET	/admin/allUsers
👤 4. Profile Management

🔒 Requires Authentication

Method	Endpoint	Description
GET	/customer/profile	Get profile
POST	/customer/profile	Update profile
POST	/customer/profile/change-password	Change password
DELETE	/customer/profile/avatar	Delete avatar
DELETE	/customer/profile/account	Delete account
Update Profile (Form Data)
Field	Type
first_name	text
last_name	text
phone	text
gender	text
avatar	file
📦 5. Products
Method	Endpoint	Auth
GET	/products	Public
GET	/products/{id}	Public
GET	/products/filter	Public
POST	/products	Admin
PATCH	/products/{id}	Admin
DELETE	/products/{id}	Admin
Example Response
[
  {
    "id": 1,
    "name": "iPhone 14 Pro",
    "price": "999.00",
    "stock": 50
  }
]
📁 6. Categories
Method	Endpoint	Auth
GET	/categories	Public
GET	/categories/{id}	Public
POST	/categories	Admin
DELETE	/categories/{id}	Admin
🛒 7. Cart

🔒 Requires Authentication

Method	Endpoint
GET	/carts
POST	/carts
PATCH	/carts/{id}
DELETE	/carts/{id}
Add to Cart
{
  "product_id": 1,
  "quantity": 2
}
📦 8. Orders & Checkout
Method	Endpoint
POST	/checkout
GET	/orders
GET	/orders/{id}
Checkout Example
{
  "shipping_name": "John Doe",
  "shipping_city": "Cairo",
  "payment_method": "credit_card"
}
💳 9. Payments
Method	Endpoint	Auth
POST	/orders/{id}/payments	Yes
GET	/payments/{id}/confirm	Yes
POST	/webhooks/stripe	No
🔑 10. Authentication Header
Authorization: Bearer {your_token}
📊 11. HTTP Status Codes
Code	Meaning
200	OK
201	Created
400	Bad Request
401	Unauthorized
403	Forbidden
404	Not Found
422	Validation Error
500	Server Error
🚀 12. Installation
git clone https://github.com/marwan2096/amazon-api.git
cd amazon-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan storage:link
php artisan serve
🔒 13. Roles & Permissions
Role	Permissions
Admin	Full access
Customer	Orders & cart
Delivery	Delivery updates
📧 14. OTP & Email
OTP is 6 digits
Sent via email
Used for:
Account verification
Password reset
