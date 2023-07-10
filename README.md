# Petfinder API helper for New Leash Rescue website

These library will help you with the usage of Petfinder API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/albawebstudio/petfinder-api.svg?style=flat-square)](https://packagist.org/packages/albawebstudio/petfinder-api)
[![Total Downloads](https://img.shields.io/packagist/dt/albawebstudio/petfinder-api.svg?style=flat-square)](https://packagist.org/packages/albawebstudio/petfinder-api)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Packagist PHP Version](https://img.shields.io/packagist/dependency-v/albawebstudio/petfinder-api/php)](https://packagist.org/packages/albawebstudio/petfinder-api)


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

## Using in Laravel Project

You can implement the requests using the following instructions.

### Update .env

Append the following to the end of your `.env` file. You can find the API access information in the 
[Developer Settings](https://www.petfinder.com/user/developer-settings/) of your Petfinder account.

You can fetch your organization ID in several ways. Look at the [animal shelter and rescue](https://www.petfinder.com/animal-shelters-and-rescues/search/) 
page and search for your organization. In the "shelter search results", hover over the search icon in the "Pet List" 
column. Your organization ID is the value after `shelter_id=`.

For example, if you hover over and see the following URL `https://www.petfinder.com/pet-search?shelter_id=MN452`, your 
organization ID is `MN452`.

```shell
# PETFINDER

PETFINDER_API_KEY=
PETFINDER_API_SECRET=
PETFINDER_ORGANIZATION_ID=
```

Create a new controller for making the API requests. Our example we have created a controller `PetfinderApiController`.

```php
<?php

namespace App\Http\Controllers;



use albawebstudio\PetfinderApi\PetfinderConnector;

class PetfinderApiController extends Controller
{
    public function __construct()
    {
        PetfinderConnector::init(
            config('petfinder.key'),
            config('petfinder.secret'),
            config('petfinder.organization'),
        );
    }
}

```

The next step is to generate a new controller for fetching the `animals` data. We created a controller `AnimalController`.

*Note: The controller will extend the `PetfinderApiController`.

```php
<?php

namespace App\Http\Controllers;


use albawebstudio\PetfinderApi\Animal;
use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;

class AnimalController extends PetfinderApiController
{
    /**
     * @var Animal
     */
    protected Animal $api;

    public function __construct()
    {
        parent::__construct();
        $this->api = new Animal();
    }

    /**
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function index()
    {
        return $this->api->animals([]);
    }

    /**
     * @param int $animalId
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function show(int $animalId): array
    {
        return $this->api->animal($animalId);
    }

    /**
     * Return all animal types from Petfinder
     *
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function types(): array
    {
        return $this->api->types();
    }

    /**
     * Return specific animal type from Petfinder
     *
     * @param string $type
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function type(string $type): array
    {
        return $this->api->type($type);
    }

    /**
     * Return all breeds for specific animal type from Petfinder
     * @param string $type
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function breeds(string $type): array
    {
        return $this->api->breeds($type);
    }
}


```

The `AnimalController` has the methods necessary to find all the animal information currently available through the 
Petfinder API. Be sure to check out all the [query parameters](https://www.petfinder.com/developers/v2/docs/#get-animals) 
available.

You have the option to generate a new controller for fetching the `organizations` data. We created a controller 
`OrganizationController`.

*Note: The controller will extend the `PetfinderApiController`.

```php
<?php

namespace App\Http\Controllers;

use albawebstudio\PetfinderApi\exceptions\InvalidAuthorizationException;
use albawebstudio\PetfinderApi\exceptions\InvalidRequestException;
use albawebstudio\PetfinderApi\exceptions\PetfinderConnectorException;
use albawebstudio\PetfinderApi\Organization;

class OrganizationController extends PetfinderApiController
{
    /**
     * @var Organization
     */
    protected Organization $api;
    public function __construct()
    {
        parent::__construct();
        $this->api = new Organization();
    }

    /**
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function index(): array
    {
        return $this->api->organizations([]);
    }

    /**
     * @param string $organizationId
     * @return array
     * @throws InvalidAuthorizationException
     * @throws InvalidRequestException
     * @throws PetfinderConnectorException
     */
    public function show(string $organizationId): array
    {
        return $this->api->organization($organizationId);
    }
}


```

The `OrganizationController` has the methods necessary to find all the organizations currently available through the
Petfinder API. Be sure to check out all the [query parameters](https://www.petfinder.com/developers/v2/docs/#get-organizations)
available.

### API Routes

Here is an example of how to set up the API routes. Added the following to `routes/api.php`

```php

Route::prefix('animals')->group(function() {
    Route::get('/', 'App\Http\Controllers\AnimalController@index')->name('animals');
    Route::get('/{id}', 'App\Http\Controllers\AnimalController@show')->name('animal');
});

Route::prefix('types')->group(function () {
    Route::get('/', 'App\Http\Controllers\AnimalController@types')->name('types');
    Route::get('/{type}', 'App\Http\Controllers\AnimalController@type')->name('type');
    Route::get('/{type}/breeds', 'App\Http\Controllers\AnimalController@breeds')->name('breeds');
});

Route::prefix('organizations')->group(function() {
    Route::get('/', 'App\Http\Controllers\OrganizationController@index')->name('organizations');
    Route::get('/{id}', 'App\Http\Controllers\OrganizationController@show')->name('organization');
});

```