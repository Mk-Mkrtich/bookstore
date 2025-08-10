# Laravel Reservation API

This project is a REST API built with Laravel 12, using JWT authentication.

## Technology Stack

Laravel 12

REST API only

JWT Authentication

Mysql DB

---

## Getting Started

### Step 1: Clone the repository

```bash

git clone https://github.com/Mk-Mkrtich/bookstore.git
cd bookstore
```

### Step 2: Configure Database
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 3: Install Dependencies and Setup Database

```bash

composer install
php artisan migrate
php artisan db:seed
```

### Test Users

You can use the following test accounts to authenticate and test the API:

```angular2html
Role: User
Name: Test User
Email: user@example.com
Password: password123
```
```angular2html
Role: Admin
Name: Test Admin
Email: admin@example.com
Password: password123
```


---


### Commands For Cancel Expired Reservations

```bash

php artisan app:expire-reservations
```
### Example output: 

```
Expired 2 reservations.
```

### Check the scheduled commands and their next run time:

```bash

php artisan schedule:list
```
### Example output: 

```
* * * * *  App\Console\Commands\ExpireReservations ...... Next Due: 13 seconds from now
```


## Postman Collection

You can download and import the Postman collection to test the API:

[📥 Download Postman Collection](./onex_test_task.postman_collection.json)

To import:
1. Open Postman.
2. Click **Import**.
3. Select the downloaded `postman_collection.json`.


