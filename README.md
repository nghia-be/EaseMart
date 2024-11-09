<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Maintain Dev

- [TrinhHDP](https://fpt.workplace.com/profile.php?id=100076727237575).
- [OanhLTN3](https://fpt.workplace.com/profile.php?id=100074294720393).

### Set Up Project

Required PHP 7.x
Standard config, cd to folder project
- php artisan migrate
- php artisan key:generate
- php artisan serve

Docker config, cd to folder project
- docker-compose up -d
- php artisan migrate
- php artisan key:generate
- Edit .env file: DB_PASSWORD=trinhdev
- php artisan serve

To use VITE JS hot reload blade
- npm i
- npm run dev
- npm run build

To use api 
- secret_key: hiadminapi_2022
- send token when call api: Authorization md5(secret_key + :: + secret_key + date('Y-d-m'))

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

ERROR 500
php artisan key:generate
php artisan cache:clear
php artisan config:clear
composer dump-autoload
