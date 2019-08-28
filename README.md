# Laravel package for Stripe Payments

A little Laravel package to communicate with Stripe.

## Installation

You can install the package via composer:

```bash
composer require macsidigital/laravel-stripe
```

The service provider should automatically register for For Laravel > 5.4.

For Laravel < 5.5, open config/app.php and, within the providers array, append:

``` php
MacsiDigital\Stripe\Providers\StipeServiceProvider::class
```

## Configuration file

Publish the configuration file

```bash
php artisan vendor:publish --provider="MacsiDigital\Stripe\Providers\StripeServiceProvider"
```

This will create a xero/config.php within your config directory. Check the Stripe documentation for the relevant values in the config.php file.
Ensure that the location of the RSA keys matches.

## Usage

Everything has been setup to be similar to Laravel syntax.

### Testing

At present there is no PHP Unit Testing, but we plan to add it in the future.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email colin@macsi.co.uk instead of using the issue tracker.

## Credits

- [Colin Hall](https://github.com/macsidigital)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
