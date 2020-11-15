# Laravel ConsoleLogg
[![Latest Stable Version](https://poser.pugx.org/devthis/console-logg/v)](https://packagist.org/packages/devthis/console-logg)
[![codecov](https://codecov.io/gh/dev-this/laravel-console-logg/branch/master/graph/badge.svg)](https://codecov.io/gh/dev-this/laravel-console-logg)
[![Total Downloads](https://poser.pugx.org/devthis/console-logg/downloads)](https://packagist.org/packages/devthis/console-logg)
[![License](https://poser.pugx.org/devthis/console-logg/license)](https://packagist.org/packages/devthis/console-logg)
[![FOSSA Status](https://app.fossa.com/api/projects/custom%2B21424%2Fgit%40github.com%3Adev-this%2Flaravel-console-logg.git.svg?type=shield)](https://app.fossa.com/projects/custom%2B21424%2Fgit%40github.com%3Adev-this%2Flaravel-console-logg.git?ref=badge_shield)

#### Effortless PSR-3 Logger output to your console applications
Powered by [Symfony's Console Logger](https://symfony.com/doc/current/components/console/logger.html)

## Table of contents

- [Install](#Install)
- [Features](#available-methods)
- [Example](#Example)
- [License](#license)

# What does it do?

This pacakge allows you to send output to a console application using the built-in Laravel Logger (PSR-3 Interface, or Log facade).

It works out of the box with zero configuration.


## Install

1. Install the package via Composer:

    ```shell script
   composer require devthis/console-logg
   
   # to optionally copy config
   php artisan vendor:publish --provider="DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider"
    ```

# Features

## Zero configuration
ConsoleLogg service provider will be automatically added as a provider for your application.

However, if installed with `composer require --no-scripts` you will need to manually add the provider into your `config/app.php`
```php
'providers' => [
    //...
    \DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider::class,
];
```

Otherwise you can manually add the provider

## Efficient
- **Zero external dependencies** outside of Laravel contracts
- No external dependencies
- Tested for memory leaks
  - One time use console logger is attached & detached as required
  - All references destroyed after command termination (letting PHP Garbage Collector to do its thing)
- Service Provider does not register components when initialized when using non-CLI mode (ie. web)

## Safe
- ConsoleLogg has 100% code coverage with unit tests
  - PCov is used for accurate branch analysis 
  - `@covers` annotations used in unit tests for contextual line coverage 
- Compatibility thoroughly tested through real world tests
  - All tests are run independently against PHP 7.1, 7.2, 7.3, 7.4 * each Laravel contracts, of each major version + all minor versions above 5.5
     - Unit tests ensure type-compatibility, expected behaviour is met & compatibility with each version of Laravel contracts
     - [TODO] Functional tests ensure real world expectations are through a real Laravel application

## Verbosity
- Respects the default built-in Symfony verbosity mappings

| Verbosity | Column header 2 |
| ---------------|----------------|
| -v | `emergency`, `alert`, `critical`, `error`, `warning`|
| -vv |`notice` + all of above |
| -vvv | `info` + all of above |

`debug` level logs will only be present when `APP_DEBUG=1` & `-vvv` is used

## Works with command-in-command

ConsoleLogg has been tested & is compatible with [nested command calls](https://laravel.com/docs/8.x/artisan#calling-commands-from-other-commands
)

## Configuration

See [config/console-logg.php](config/console-logg.php).

# Example
Example Service

```php
namespace App\Service;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class MyExampleService {

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function doSomethingCool(): void
    {
        $this->logger->debug("A message that may be meaningful to a console app using this service");
        
        Log::info("or just the facade if you love magic");
        
        // ...
    }
}
```

Example Console Application
```php
namespace App\Console\Commands;

use App\Service\ExampleService;
use Illuminate\Console\Command;

class ExampleConsole extends Command
{
    /**
     * The console command description.
     */
    protected $description = '';

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'something';

    public function handle(ExampleService $exampleService): int
    {
        $exampleService->doSomethingCool();

        return 0;
    }
}

```

Running the console
```bash
not-root@linux:~/MyProject$ php artisan something
[debug] A message that may be meaningful to a console app using this service
[info] or just the facade if you love magic
```

## License

Laravel ConsoleLogg is open-sourced software licensed under the [MIT license](LICENSE.md).
