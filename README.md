# A .env replacement for storing secret credentials in your Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcode/laravel-credentials.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-credentials)
[![Build Status](https://img.shields.io/travis/beyondcode/laravel-credentials/master.svg?style=flat-square)](https://travis-ci.org/beyondcode/laravel-credentials)
[![Quality Score](https://img.shields.io/scrutinizer/g/beyondcode/laravel-credentials.svg?style=flat-square)](https://scrutinizer-ci.com/g/beyondcode/laravel-credentials)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcode/laravel-credentials.svg?style=flat-square)](https://packagist.org/packages/beyondcode/laravel-credentials)

The `beyondcode/laravel-credentials` package allows you to store all your secret credentials in an encrypted file and put that file into version control instead of 
having to add multiple credentials into your `.env` file.

There are a couple of benefits of using encrypted credentials instead of environment keys:

* Your credentials are encrypted. No one will be able to read your credentials without the key.
* The encrypted credentials are saved in your repository. You'll have a history of the changes and who made them.
* You can deploy credentials together with your code.
* All secrets are in one location. Instead of managing multiple environment variables, everything is in one file.

Here's how you can access your stored credentials:

```php
$secret = credentials('my-secret-credentials');
```

With the built-in edit command, you can easily edit your existing credentials. They will be automatically encrypted after saving your changes.

```bash
php artisan credentials:edit
```
![Credentials Demo](https://beyondco.de/github/credentials.gif)


## Installation

You can install the package via composer:

```bash
composer require beyondcode/laravel-credentials
```

The package will automatically register itself.

You can optionally publish the configuration with:

```bash
php artisan vendor:publish --provider="BeyondCode\Credentials\CredentialsServiceProvider" --tag="config"
``` 

This is the content of the published config file:

```php
<?php

return [

    /*
     * Defines the file that will be used to store and retrieve the credentials.
     */
    'file' => config_path('credentials.php.enc'),

    /*
     * Defines the key that will be used to encrypt / decrypt the credentials.
     * The default is your application key. Be sure to keep this key secret!
     */
    'key' => config('app.key'),

    'cipher' => config('app.cipher'),

];
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email marcel@beyondco.de instead of using the issue tracker.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
