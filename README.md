# Laravel Addressing

[![Build Status](https://travis-ci.org/glhd/laravel-addressing.svg)](https://travis-ci.org/glhd/laravel-addressing)

> Laravel package providing addressing functionality

## Installation

First, install the composer package:

```
composer require galahad/laravel-addressing
```

## Basic Usage

## 1.0 Breaking Changes
 - AdministrativeArea has been re-named Subdivision
 - getAdministrativeArea/etc as well
 - Things have been moved around
 - findAdministrativeArea is removed
 - Country::getPostalCodePattern -> Country::addressFormat::getPostalCodePattern

### Country

```php
$country = Addressing::country('US');
echo $country->getName(); // United States
```

### Administrative Areas (States)

```php
echo Addressing::country('US')->administrativeArea('AL')->getName(); // Alabama
```

```php
$administrativeAreas = Addressing::country('BR')->administrativeAreas();
foreach ($administrativeAreas as $administrativeArea) {
    echo sprint("[%s]: %s\n", $administrativeArea->getCode(), $administrativeArea->getName());
}
```

## Validators

You can use some custom validators in your Laravel app:

### Countries

You can use `country_code` and `country_name` validators:

```php
$this->validate($request, [
    'country' => 'required|country_code',
]);

$this->validate($request, [
    'country' => 'required|country_name',
]);
```

### Administrative Areas (States)

You can use `administrative_area_code`, `administrative_area_name` or `administrative_area` (verifies both `code` and `name`):

```php
$this->validate($request, [
    'state' => 'required|administrative_area_code:country_field',
]);

$this->validate($request, [
    'state' => 'required|administrative_area_name:country_field',
]);

$this->validate($request, [
    'state' => 'required|administrative_area:country_field', // verifies first code and after name
]);
```

### Postal Code

You can check if the postal code starts with the correct pattern using `postal_code` validator:

```php
$this->validate($request, [
    'postal_code' => 'required|postal_code:country_field,administrative_area_field',
]);
```

## API

You can also get Countries and Administrative Areas (states) in `JSON` format:

```json
// GET /galahad/addressing/countries
{
    "label": "Countries",
    "options": {
        "AF": "Afghanistan",
        "**": "*******",
        "ZW": "Zimbabwe"
    }
}
// If error
{
    "error": true,
    "message": "Could not get countries"
}

// GET /galahad/addressing/US/adminstrative-areas
{
     "label": "State",
     "expected_length": 2,
     "country": "US",
     "options": {
        "AL": "Alabama",
        "**": "*******",
        "WY": "Wyoming"
     }
}
```

### Setting custom Locales

You can get the countries list using a custom locale:

```
GET /galahad/addressing/countries?locale=pt
```

### Changing the route group prefix

By default the routes returning the JSON responses are prefixed with `galahad/addressing`. If you would like to change this, you need to publish the configuration file using `php artisan vendor:publish --provider="Galahad\LaravelAddressing\ServiceProvider"`. This will create a config file (`addressing.php`) in your `config` directory with:

```php 
<?php

return [
    'route' => [
        'prefix' => 'countries' // change this to whatever you'd like
    ],
];
```


### Thanks!

Special thanks to [Commerce Guys](https://github.com/commerceguys) for their amazing [addressing](https://github.com/commerceguys/addressing) and [intl](https://github.com/commerceguys/intl) packages, which this project relies heavily on.
