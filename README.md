## Validation

A validation manager made more flexible validators in [Laravel](https://laravel.com/docs)

[![Total downloads](https://img.shields.io/packagist/dt/nodes/validation.svg)](https://packagist.org/packages/nodes/validation)
[![Monthly downloads](https://img.shields.io/packagist/dm/nodes/validation.svg)](https://packagist.org/packages/nodes/validation)
[![Latest release](https://img.shields.io/packagist/v/nodes/validation.svg)](https://packagist.org/packages/nodes/validation)
[![Open issues](https://img.shields.io/github/issues/nodes-php/validation.svg)](https://github.com/nodes-php/validation/issues)
[![License](https://img.shields.io/packagist/l/nodes/validation.svg)](https://packagist.org/packages/nodes/validation)
[![Star repository on GitHub](https://img.shields.io/github/stars/nodes-php/validation.svg?style=social&label=Star)](https://github.com/nodes-php/validation/stargazers)
[![Watch repository on GitHub](https://img.shields.io/github/watchers/nodes-php/validation.svg?style=social&label=Watch)](https://github.com/nodes-php/validation/watchers)
[![Fork repository on GitHub](https://img.shields.io/github/forks/nodes-php/validation.svg?style=social&label=Fork)](https://github.com/nodes-php/validation/network)
[![StyleCI](https://styleci.io/repos/49194830/shield)](https://styleci.io/repos/49194830)

## 📝 Introduction

Validation is something we take quite serious in [Nodes](http://nodesagency.com) which means we've had cases where the default Laravel validator
simply doesn't cut it.

Therefore we've created this little neat package, which makes it super easy to create your own validator and add your own custom rules.
By default this package uses our own Validator with our own custom rules, but you can easily swap it out with your own stuff.

## 📦 Installation

To install this package you will need:

* Laravel 5.1+
* PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```json
"require": {
    "nodes/validation": "^1.0"
}
```

Or you can run the composer require command from your terminal.

```bash
composer require nodes/validation:^1.0
```

## 🔧 Setup
> In Laravel 5.5 or above, service providers and aliases are [automatically registered](https://laravel.com/docs/5.5/packages#package-discovery). If you're using Laravel 5.5 or above, skip ahead directly to *Publish config files*.

Setup service provider in `config/app.php`

```php
Nodes\Validation\ServiceProvider::class
```

Publish config files

```bash
php artisan vendor:publish --provider="Nodes\Validation\ServiceProvider"
```

If you want to overwrite any existing config files use the `--force` parameter

```bash
php artisan vendor:publish --provider="Nodes\Validation\ServiceProvider" --force
```

## ⚙ Usage

Create a new validator and make it extend `Nodes\Validation\Validator`.

At a later time, we'll update the documentation with details about each rule this package comes with out-of-the-box.
But for now, we recommend you to take a look in the source code and read the DocBlock of each method.

Check out the available rules in the [src/Rules](https://github.com/nodes-php/validation/tree/master/src/Rules) directory.

## 🏆 Credits

This package is developed and maintained by the PHP team at [Nodes Agency](http://nodesagency.com)

[![Follow Nodes PHP on Twitter](https://img.shields.io/twitter/follow/nodesphp.svg?style=social)](https://twitter.com/nodesphp) [![Tweet Nodes PHP](https://img.shields.io/twitter/url/http/nodesphp.svg?style=social)](https://twitter.com/nodesphp)

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
