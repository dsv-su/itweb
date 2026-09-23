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

## Monthly project proposal statistics

In **Vice Head settings → Notifications → Monthly statistics**, select SUKAT users and click **Update**. An empty recipient list disables delivery. Run database migrations when deploying this feature.

The Laravel scheduler sends individual HTML and plain-text summaries at **21:00 Europe/Stockholm on the first of each month**. As with the other scheduled commands, the server must run `php artisan schedule:run` every minute (or run `schedule:work`). No queue worker is needed for this command.

Reports follow the existing proposal statistics convention: sent/granted proposals with submission deadlines in the previous calendar month, using statuses and budgets at report generation time. They include counts, research subjects, funding organizations, planned PhD years, and DSV budgets/co-financing separated by currency.

To check the reporting month and recipient count without sending mail:

```sh
php artisan proposals:send-monthly-statistics --dry-run
```

To retry failed deliveries for the previous month, run `php artisan proposals:send-monthly-statistics`. Successful deliveries are recorded per month/email and skipped on reruns. Failures are logged and do not prevent other recipients from receiving the report. As with SMTP delivery generally, a process failure after sending but before recording success can cause a duplicate on retry.

To preview a specific reporting month, use `php artisan proposals:send-monthly-statistics --month=2026-08 --dry-run`. Omit `--dry-run` to send that month's report to the configured recipients; previously successful deliveries for that month remain skipped. Without `--month`, the scheduled command continues to use the previous calendar month.
