## 1. Overview
Production setup for DSV it webb.

## 2. Requirements
Requirements are best determined using Server Requirements page of corresponding Laravel 12

- PHP version 8.3.*

- BCMath PHP Extension

- Ctype PHP Extension

- Fileinfo PHP Extension

- LDAP PHP Extension

- JSON PHP Extension

- Mbstring PHP Extension

- OpenSSL PHP Extension

- PDO PHP Extension

- Tokenizer PHP Extension

- XML PHP Extension

- SSH access to the server

- Composer

## 3. Installation

* Make sure that composer is installed globally or install it in place

    * Clone the repository

        * Move into the directory. Move into the `/systemconfig` folder.

            * Make sure that `/systemconfig/it.ini` file is present and configured with the configuration details for your requirements and for the server (copy internt.ini.example to .ini and fill in with your data)


* Make sure the subdirectories
  `bootstrap/cache` and `/storage` is writable by your web server user.

Make sure these folder exist or create these folders under storage/framework:

    sessions 
    views
    cache

* Once the global settings are entered you can install the dependencies. `composer install`

    * Make sure that .env file is present (copy .env.example to .env). If you are setting up a dev enviroment add the following settings to the .env file:


    EMULATE_IDP=true
    SHIBB_NAME=Shib-cn
    SHIBB_FNAME=Shib-givenName
    SHIBB_LNAME=Shib-sn
    SHIBB_EMAIL=Shib-mail
    SHIBB_EMPLID=Shib-emplId


* Either create application key manually or do that with a command `php artisan key:generate`

* Create the database with `php artisan migrate` (this should create database tables needed)


## 4. Building assets (dev)

Make sure you have updated npm to the latest version

    npm update -g

Install the dependecies

    npm install

Build the development assets by running

    npm run dev

For production build the production assets

    npm run build
