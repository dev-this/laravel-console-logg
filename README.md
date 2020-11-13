# Laravel ConsoleLogg

Effortless PSR-3 Logger output to your console applications. Powered
by [Symfony's Console Logger](https://symfony.com/doc/current/components/console/logger.html)

Allows your to use the Log facade, or PSR-3 LogInterface throughout your application, and have the output rendered to
console applications.

| Laravel | Compatible out of the box |
| ------- | :-----------------------------------------------------------------------: |
| 8.x     | Yes |
| 7.x     | Yes |
| 6.x     | Yes |
| 5.8.*   | Not yet |
| 5.7.*   | Not yet |
| 5.6.*   | Not yet |

## Table of contents

- [Usage](#usage)
    - [Example](#channels)
- [Configuration](#available-methods)
- [Extending](#channels)
- [License](#license)

## Why?

Because your application shouldn't be coupled to a concrete class explicitly for console logging.

This allows your API/workers/console to have the same logging interface, and messages

## Usage

1. Install the package via Composer:

    ```shell script
   composer require devthis/console-logg
   
   # to optionally copy config
   php artisan vendor:publish --provider="DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider"
    ```

2. Add some `Log::info('Informative logs');` throughout your application

### Example

## Configuration

## Extending

## License

## License

Laravel ConsoleLogg is open-sourced software licensed under the [MIT license](LICENSE.md).
