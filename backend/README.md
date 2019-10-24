# Live Tree view application 
Author: Sergei Safarov <inbox@sergeysafarov.com>

This is the backend (api) part of live tree view application.

## Getting started
### Requirements
* MySQL server 5.6+
* PHP 7.x
* Composer


### Installation
Before run the application you will need to make several preparations steps.
* Goto application backend folder
```bash
cd backend
```

* Install packages by runing the following commands:
```bash
composer install
```
* Create database
* Rename **.env.example** file into **.env** file and set the correct database name, database user and database user password.   
* Run the database migrations and seeds:
```bash
php artisan migrate --seed
```
* Finalize lumen application setup by the following command:
```bash
composer dump-autoload
```
* Run built-in web server or point **/public** folder as webroot for existing:
```bash
php artisan serve
```
You will get console notification about running server (in case of built-in server): 
```
> Laravel development server started: <http://127.0.0.1:8000>
```
Now you can test backend part by entering either http://127.0.0.1:8000 or any other url you've setup and connected with this app.
You should get the similar output:
```
{"status":200,"message":"Live Tree View based on Lumen (6.2.0) (Laravel Components ^6.0)","data":[]}
```

## License
MIT: [http://mit-license.org](http://mit-license.org)
