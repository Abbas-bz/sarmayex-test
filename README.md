
# Sarmayex tasks

This is a sample project for Sarmayex

## Requirements

PHP 8.0
MySQL connection

### Steps to run application

Set up environment variables required in .env.example


Installl composer dependencies

`composer install`

Run migrations and install passport:

`php artisan migrate`

`php artisan passport:install`

Run seeder to add Sample users:

`php artisan db:seed`