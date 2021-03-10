Service Law
Relations -> Plectica.
Quick start
1. Clone project
   git@gitlab.alifshop.uz:alifuz/backend/service-merchant-laravel.git
2. Copy .env.example file to .env
   cp .env.example .env
3. Install composer and npm:
   composer install
   or
   composer install --ignore-platform-reqs
   npm install
4. Generate unique application key:
   php artisan key:generate
5. Now we need to fill this fields in .env as in your local db. For Example:
   DB_DATABASE=service_merchants DB_USERNAME=service_merchants DB_PASSWORD=123

Run migrations and seeds:

php artisan migrate:fresh --seed
6. run in local server:
   php artisan serve
