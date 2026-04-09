 Amazon API - Quick Setup
Prerequisites
PHP >= 8.1

MySQL >= 5.7
# 1. AMAZON E-COMMERCE API DOCUMENTATION
https://github.com/marwan2096/amazon-api/blob/main/amazon%20api.pdf
Composer
# 1. AMAZON E-COMMERCE API DOCUMENTATION
https://github.com/marwan2096/amazon-api/blob/main/amazon%20api.pdf
⚡ Quick Installation
bash
# 1. Clone repository
git clone https://github.com/marwan2096/amazon-api.git

# 2. Enter project folder
cd amazon-api

# 3. Install dependencies
composer install

# 4. Create environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Configure database in .env
DB_DATABASE=amazon
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations
php artisan migrate

# 8. Seed database (roles & permissions)
php artisan db:seed --class=RolesAndPermissionsSeeder

# 9. Create storage link
php artisan storage:link

# 10. Start server
php artisan serve
✅ Done!
Server running at: http://localhost:8000

📝 Default Credentials
Role	Email	Password
Admin	Check seeder file	Check seeder file
Customer	Register via /api/customer/register	Your choice
🧹 Reset Database (Optional)
bash
php artisan migrate:fresh --seed --class=RolesAndPermissionsSeeder
Need full documentation? See API_DOCUMENTATION.md


























