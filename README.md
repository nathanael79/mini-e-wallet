# Mini E-Wallet
Dibuat menggunakan lumen untuk test privy.id

### System requirements
- PHP >= 7.2
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension

### Tata Cara Instalasi
  - Clone project ini dengan git clone
  - Lakukan pemasangan asset menggunakan `composer install` didalam hasil clone project
  - Jangan lupa untuk lakukan pengisian pada .env sesuai dengan environment mysql yang ada seperti host, dbname, user, port dan password
  - Gunakan perintah `php artisan migrate --seed` untuk melakukan migrasi database dan pengisian data user
  - Gunakan `php -S localhost:8000 -t public` untuk menyalakan aplikasi
  - Akses `localhost:8000/key` untuk mendapatkan key, copy paste key yang tergenerate dan tempelkan pada .env pada bagian `APP_KEY`
  
### Dokumentasi API

 - Login user (localhost:8000/api/v1/login)
   
   | Parameter | Type | Required | Value
   | ------ | ------ | ------ | ------ |
   | email | string | Yes | admin@admin.com |
   | password | string | Yes | admin123 |
   
   setelah login, anda akan mendapatkan akses token, gunakan akses token tersebut pada seluruh header api yang ada dengan:
   `key: Authorization` dan `bearer {token}`
   
 - Create User Balance (localhost:8000/api/v1/user-balance)
   
   | Parameter | Type | Required | Value
   | ------ | ------ | ------ | ------ |
   | user_id | int | Yes | ------ |
   | balance | int | Yes | ------ |
   | type | string | Yes | credit or debit |
   
 - Create Bank Balance (localhost:8000/api/v1/bank-balance)
 
    | Parameter | Type | Required | Value
    | ------ | ------ | ------ | ------ |
    | code | int | Yes | nomor rekening |
    | balance | int | Yes | ------ |
    | type | string | Yes | credit or debit |
    | user_agent | string | Yes | Bank Debit |
    
 - Transfer to Bank (localhost:8000/api/v1/transfer)
     
    | Parameter | Type | Required | Value
    | ------ | ------ | ------ | ------ |
    | user_id | int | Yes | ------ |
    | code | int | Yes | nomor rekening |
    | balance | int | Yes | ------ |

 - Top up from Bank (localhost:8000/api/v1/retrieve)
     
    | Parameter | Type | Required | Value
    | ------ | ------ | ------ | ------ |
    | user_id | int | Yes | ------ |
    | code | int | Yes | nomor rekening |
    | balance | int | Yes | ------ |

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)
