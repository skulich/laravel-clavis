# Laravel Clavis

[![Latest Version on Packagist](https://img.shields.io/packagist/v/skulich/laravel-clavis.svg)](https://packagist.org/packages/skulich/laravel-clavis)
![PHP Version Require](https://img.shields.io/packagist/php-v/skulich/laravel-clavis)
![Laravel Version](https://img.shields.io/badge/laravel-%5E12.0%20-red?logo=laravel)
[![Run Tests](https://github.com/skulich/laravel-clavis/actions/workflows/tests.yml/badge.svg)](https://github.com/skulich/laravel-clavis/actions)
![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)
![License](https://img.shields.io/packagist/l/skulich/laravel-clavis.svg)
![Total Downloads](https://img.shields.io/packagist/dt/skulich/laravel-clavis.svg)

**Laravel Clavis** is a lightweight token-based authentication middleware package for Laravel APIs.

Perfect for **API-first applications** and **microservices** where you need simple and secure token-based authentication
without the overhead of Sanctum.

**Key benefits:**

- 🚀 **Quick Setup**: Create a token via CLI
- 🔒 **Secure**: Built on Laravel's native Hash generator
- 🎯 **Focused**: Designed for server-to-server scenarios
- 🧹 **Clean**: No migrations, No users, No dependencies

# Table of contents

* [Installation](#installation)
* [Usage](#usage)
    * [CLI Command](#cli-command)
    * [API Routes](#api-routes)
* [User Model Binding](#user-model-binding)
* [Tests](#tests)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [License](#license)

## Installation

Install the package via Composer.

```shell
composer require skulich/laravel-clavis
```

## Usage

## CLI Command

Create a new API token via CLI.

```shell
php artisan clavis:token
```

## API Routes

Add the `clavis` middleware to your API routes.

```php
// Per Route
Route::get('/test', function (Request $request) {
    // return ...
})->middleware('clavis');

// Per Group

Route::middleware('clavis')->group(function () {
    // Route:: ...
});
```

## Tests

Run the entire test suite:

```shell
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for more information.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE.md) for more information.
