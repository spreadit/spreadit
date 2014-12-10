# Bathroom Stall for Laravel 4

A bathroom stall for you to scratch on. Uses redis backend and canvas.

## Installation

The Bstall Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the
`whackashoe/bstall` package and setting the `minimum-stability` to `dev` (required for Laravel 4) in your
project's `composer.json`.

```json
{
    "require": {
        "laravel/framework": "4.0.*",
        "whackashoe/bstall": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Update your packages with ```composer update``` or install with ```composer install```.

## Usage

To use the Captcha Service Provider, you must register the provider when bootstrapping your Laravel application. There are
essentially two ways to do this.

Find the `providers` key in `app/config/app.php` and register the Captcha Service Provider.

```php
    'providers' => array(
        // ...
        'Whackashoe\Bstall\BstallServiceProvider',
    )
```

Find the `aliases` key in `app/config/app.php`.

```php
    'aliases' => array(
        // ...
        'Bstall' => 'Whackashoe\Bstall\Facades\Bstall',
    )
```

## Example Usage

```twig

    <html>
        <head>
            <title>stall test</title>
        </head>
        <body>
            {{ Bstall::make("first_stall", 300, 300, 0xFFFFFF) }}
        </body>
    </html>
```

To clear all stalls quickly just run `php artisan cache:clear`