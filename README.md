# Petfinder API helper for New Leash Rescue website


## Installation

Require this package with composer. It is recommended to only require the package for development.

```shell
composer require albawebstudio/petfinder-api
```

Laravel uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

### Laravel without auto-discovery:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```php
albawebstudio\PetfinderApi\ServiceProvider::class,
```

You can also set in your config with Petfinder API key, API secret and organization ID with .env vars.

#### Copy the package config to your local config with the publish command:

```shell
php artisan vendor:publish --provider="albawebstudio\\PetfinderApi\\ServiceProvider"
```
