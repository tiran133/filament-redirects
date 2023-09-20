# Redirects package for Filament and Laravel

This package allows you to add redirects via Filament manually or with an import via Excel or csv

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
    'route-wildcard' => '*',
    'default-status' => 302,
];
```

### Enabling the package

To make the redirects work, you have to register the Redirects middleware

```php
// app/Http/Kernel.php

protected $middleware = [
    // ...
    \Codedor\FilamentRedirects\Http\Middleware\Redirects::class,
];
```

And also register the plugin in your Panel provider:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            \Codedor\FilamentRedirects\Filament\RedirectsPlugin::make(),
        ]);
    }

```

## Configuration

This package has a couple of config values:

```php
<?php

return [
    'route-wildcard' => '*',
    'default-status' => 302,
];
```

### route-wildcard

This determines what the wildcard is in the CMS. By default this is `*`, but can be changed for whatever reason if necessary.

### default-status

The default HTTP status that the redirects uses, if none is given.

