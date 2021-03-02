# Laravel Route Summary

[![Latest Version on Packagist](https://img.shields.io/packagist/v/biscofil/laravel-route-summary.svg?style=flat-square)][link-packagist]

[![Total Downloads](https://img.shields.io/packagist/dt/biscofil/laravel-route-summary.svg?style=flat-square)][link-downloads]

[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


Create an HTML graphical summary of all the routes of your Laravel project. The package also checks for the binding type of each method's argument, returning an error if the parameter name specified in the route definition does not match the one in the contoller.

![Image description](screenshot.png)

The routes are exported in both Html/Json files where they are represented in the following format:

```json
[
    {
        "uri": "\/",
        "name": "homepage",
        "controller": "App\\Http\\Controllers\\HomeController",
        "controller_method": "index",
        "parameters": [],
        "methods": [
            "GET"
        ],
        "middleware": [
            "web"
        ]
    },
    {
        "uri": "new",
        "name": "new_foo",
        "controller": "App\\Http\\Controllers\\Auth\\RegisterController",
        "controller_method": "index",
        "parameters": [],
        "methods": [
            "GET"
        ],
        "middleware": [
            "web",
            "guest"
        ]
    }
]
```

## Installation

```sh
composer require --dev biscofil/laravel-route-summary
```

## Usage

```sh
php artisan route:summary
```

## Credits

- [Filippo Bisconcin][link-author]

## License

license. Please see the [license file](LICENSE) for more information.

[ico-travis]: https://api.travis-ci.org/biscofil/laravel_route_summary.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/biscofil/laravel-route-summary
[link-downloads]: https://packagist.org/packages/biscofil/laravel-route-summary
[link-travis]: https://travis-ci.org/biscofil/laravel_route_summary
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/biscofil
[link-contributors]: ../../contributors
