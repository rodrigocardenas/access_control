# Access Control REST APi
This API is created using Laravel 7.29 API Resource. It has Users and Buildings. Protected routes are also added. Protected routes are accessed via Passport access token.
##### PHP 7.4 is required!

#### Following are the Models
* User
* Building
* AccessLog

#### Usage
Clone the project via git clone or download the zip file.

##### .env
Copy contents of .env.example file to .env file. Create a database and connect your database in .env file.
##### Composer Install
cd into the project directory via terminal and run the following  command to install composer packages.
###### `composer install`

##### Generate Key
then run the following command to generate fresh key.
###### `php artisan key:generate`

##### Passport Install
run the following command to install passport
###### `composer require laravel/passport "~9.0"`
##### Run Migration
then run the following command to create migrations in the databbase.
###### `php artisan migrate`
##### Install Passport
.
###### `php artisan passport:install`

### Authenticate
* Register GET `http://localhost:8000/api/register` 
    body eg. {"name":"user", "email":"email@email.com", "password":"12345678", "password_confirmation":"12345678"}
* Login GET `http://localhost:8000/api/login` 
    body eg. {"email":"email@email.com", "password":"12345678"}

### API EndPoints


##### User
* User GET `http://localhost:8000/api/user` 
* User GET Single `http://localhost:8000/api/user/1`
* User POST Create `http://localhost:8000/api/user` 
    body eg. {"name":"user", "email":"email@email.com", "password":"12345678", "password_confirmation":"12345678"}
* User PUT Update `http://localhost:8000/api/user/1`
    body eg. {"name":"user", "email":"email2@email.com", "password":"12345678", "password_confirmation":"12345678"}
* User DELETE destroy `http://localhost:8000/api/user/1`
##### Building
* building GET All `http://localhost:8000/api/building`
* building GET Single `http://localhost:8000/api/buildings/1`
* building POST Create `http://localhost:8000/api/buildings`
    body eg. {"name":"Building 1"}
* building PUT Update `http://localhost:8000/api/buildings/1`
* building DELETE destroy `http://localhost:8000/api/buildings/1`
##### AccessLog
* AccessLog POST Create `http://localhost:8000/api/user/1/storeAccess`
    body eg. {"building_id":"1", "type":"1", "date":"2021-01-12 12:00:00", "block":"sector 2"}
        type: 1='Enter', 0='Exit'
