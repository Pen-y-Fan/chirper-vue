# Chirper VUE

This is a **personal project** based on the [Laravel bootcamp](https://bootcamp.laravel.com/inertia/installation) for
Vue.

## Requirements

This is a Laravel 11 project. The requirements are the same as a
new [Laravel 11 project](https://laravel.com/docs/11.x/installation).

- [PHP 8.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org)
- [npm](https://nodejs.org/en/download/package-manager)

Recommended:

- [Git](https://git-scm.com/downloads)

## Clone

See [Cloning a repository](https://help.github.com/en/articles/cloning-a-repository) for details on how to create a
local copy of this project on your computer.

e.g.

```sh
git clone git@github.com:Pen-y-Fan/chirper-vue.git
```

## Install

Install all the dependencies using composer and npm.

```sh
cd chirper-vue
composer install
npm install
```

## Create .env

Create an `.env` file from `.env.example`

```shell script
cp .env.example .env
```

## Configure Laravel

Configure the Laravel **.env** as per you local setup. e.g.

```text
APP_NAME="Chirper VUE"
# ... other content
```

Laravel 11 can use many databases, by default the database is **sqlite**, if you have errors such as **could not find
driver (Connection: sqlite, SQL: PRAGMA foreign_keys = ON;)** make sure **extension=pdo_sqlite** is enabled
in **php.ini** (i.e. remove **;**)

## Generate APP_KEY

Generate an APP_KEY using the artisan command:

```shell script
php artisan key:generate
```

## Create the database

The **sqlite** database will need to be manually created e.g.

```shell
@php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

## Install Database

This project uses models and seeders to generate the tables for the database.

```shell
php artisan migrate
```

## Start the app

This is a Vue project which uses Vite, for development use:

```shell
npm run dev
```

## Packages

The following packages have been used:

- [laravel/breeze](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)
- [breeze:install vue](https://laravel.com/docs/11.x/starter-kits#breeze-and-inertia)

## Contributing

This is a **personal project**. Contributions are **not** required. Anyone interested in developing this project are
welcome to fork or clone for your own use.

## Credits

- [Michael Pritchard \(AKA Pen-y-Fan\)](https://github.com/pen-y-fan) original project

## License

MIT Licence (MIT). Please see [Licence File](LICENSE.md) for more information.
