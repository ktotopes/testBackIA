## Installation
You can install the project using git clone:
```phpt
git clone https://github.com/ktotopes/testBackIA
```
You can publish the configuration file using:
```phpt
composer install
```
### you will also need to create a .env file and make its configuration 
also after setting up the file .env you need to do
```phpt
php artisan migrate:fresh --seed
```
```phpt
php artisan key:generate
```

## Postman Documentation [Click](https://api.postman.com/collections/19210799-d7bbad38-ed0d-4f39-83b7-cc7b5ff3a25b?access_key=PMAT-01HKWQ7TZ1DKAVBNW1X4CK6ZBC)
```phpt
https://api.postman.com/collections/19210799-d7bbad38-ed0d-4f39-83b7-cc7b5ff3a25b?access_key=PMAT-01HKWQ7TZ1DKAVBNW1X4CK6ZBC
```
## Laravel request documentation
Paste the /api-documentation in front of the domain.
### example
```phpt
http://testbackia.test/api-documentation
```
