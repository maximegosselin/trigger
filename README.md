# Trigger

[![Latest Version](https://img.shields.io/github/release/maximegosselin/trigger.svg)](https://github.com/maximegosselin/trigger/releases)
[![Build Status](https://img.shields.io/travis/maximegosselin/trigger.svg)](https://travis-ci.org/maximegosselin/trigger)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Simple event management for PHP 7.


## System Requirements

PHP 7.0 or later.


## Install

Install using [Composer](https://getcomposer.org/):

```
$ composer require maximegosselin/trigger
```

*Trigger* is registered under the `MaximeGosselin\Trigger` namespace.


## Usage

```php
$manager = new EventManager();
```

Listen for a named event:

```php
$manager->on('login.success', function($event) { /*...*/ });
```

Listen for all events that match a regular expression:

```php
$manager->on('/^login\./', function($event) { /*...*/ });
```

Trigger an event with parameters:

```php
$manager->trigger('login.success', [
    'username' => 'jsmith'
]);
```


## Tests

Run the following command from the project folder.
```
$ vendor/bin/phpunit
```


## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
