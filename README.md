# Redirects package for Filament and Laravel

This package allows you to add redirects via Filament manually or with an import

## Installation

You can install the package via composer:

```bash
composer require codedor/filament-redirects
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-redirects-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-redirects-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-redirects-views"
```

## Usage

```php
```

## Documentation

For the full documentation, check [here](./docs/index.md).

## Testing

```bash
vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Upgrading

Please see [UPGRADING](UPGRADING.md) for more information on how to upgrade to a new version.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security-related issues, please email info@codedor.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
