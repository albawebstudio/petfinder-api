# Petfinder API helper for New Leash Rescue website

These library will help you with the usage of Petfinder API.

[![Latest Stable Version](http://poser.pugx.org/albawebstudio/petfinder-api/v)](https://packagist.org/packages/albawebstudio/petfinder-api) 
[![Total Downloads](http://poser.pugx.org/albawebstudio/petfinder-api/downloads)](https://packagist.org/packages/albawebstudio/petfinder-api) 
[![Latest Unstable Version](http://poser.pugx.org/albawebstudio/petfinder-api/v/unstable)](https://packagist.org/packages/albawebstudio/petfinder-api) 
[![License](http://poser.pugx.org/albawebstudio/petfinder-api/license)](https://packagist.org/packages/albawebstudio/petfinder-api) 
[![PHP Version Require](http://poser.pugx.org/albawebstudio/petfinder-api/require/php)](https://packagist.org/packages/albawebstudio/petfinder-api)

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
