# Laravel Auto Translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devaslanphp/auto-translate.svg?style=flat-square)](https://packagist.org/packages/devaslanphp/auto-translate)
[![Total Downloads](https://img.shields.io/packagist/dt/devaslanphp/auto-translate.svg?style=flat-square)](https://packagist.org/packages/devaslanphp/auto-translate)

This package provides a simple way to automatically generate translation JSON files for you, it helps you to generate the missing translation also.
The translation are generated automatically using Google Translations, based on the package `stichoza/google-translate-php` and exporting translations string from your source code using the package `kkomelin/laravel-translatable-string-exporter`.

# Installation

You can install the package via composer:

```shell
composer require devaslanphp/auto-translate
```

*REQUIRED*: You need to publish the package config file, so you can update the `base_locale` and `locales` list as needed:

```shell
php artisan vendor:publish --tag=auto-translate-config
```

**That's it**, you can use the package commands to generate missing translations and automatically translate them using Google Translations

# Configuration

The configuration file of this package comes like below:

```php
<?php

return [

    /*
     * 
     * Locales managed by auto-translation package, will be used by the 
     * command "auto:translate" to generate a JSON file for each of this 
     * locales, and by the command "translate:missing" to generate their
     * missing translations
     * 
     */
    'locales' => [
        'fr',
        'ar'
    ],

    /*
     * 
     * The base locale to use when using the command "translate:missing" to
     * generate missing translations for other JSON files
     * 
     */
    'base_locale' => 'fr'

];
```

I think it's well documented, I will let you check it.

## Usage

The package provides 2 Artisan commands:

### Automatic translations generation

To generate translation JSON files from your source code, you can execute the following command:

```shell
php artisan auto:translate
```

This command will check your configuration `auto-translate.locales` to generate for each locale of this list a JSON file based on your source code (`@lang()`, `__()`, ...) and translate the string into the desired locale based on Google Translations.

### Translate missing keys

The package provides also a command to let you generate translations for missing keys based on a specific JSON file (`auto-translate.base_locale`.json). To generate missing translation, you only need to execute the following command:

```shell
php artisan translate:missing
```

## Credits

- [All Contributors](https://github.com/devaslanphp/auto-translate/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
