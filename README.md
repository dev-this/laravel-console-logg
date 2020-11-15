# Laravel ConsoleLogg

![PHPUnit suite](https://github.com/dev-this/laravel-console-logg/workflows/PHPUnit%20suite/badge.svg)
[![codecov](https://codecov.io/gh/dev-this/laravel-console-logg/branch/master/graph/badge.svg)](https://codecov.io/gh/dev-this/laravel-console-logg)
[![Latest Stable Version](https://poser.pugx.org/devthis/console-logg/v)](https://packagist.org/packages/devthis/console-logg)
[![Total Downloads](https://poser.pugx.org/devthis/console-logg/downloads)](https://packagist.org/packages/devthis/console-logg)
[![License](https://poser.pugx.org/devthis/console-logg/license)](https://packagist.org/packages/devthis/console-logg)
[![FOSSA Status](https://app.fossa.com/api/projects/custom%2B21424%2Fgit%40github.com%3Adev-this%2Flaravel-console-logg.git.svg?type=shield)](https://app.fossa.com/projects/custom%2B21424%2Fgit%40github.com%3Adev-this%2Flaravel-console-logg.git?ref=badge_shield)

#### Effortless PSR-3 Logger output to your console applications

Powered by [Symfony's Console Logger](https://symfony.com/doc/current/components/console/logger.html)

## Table of contents

- [Install](#Install)
    - [Compatibility](#compatibility)
- [Features](#features)
    - [Zero config](#zero-config)
    - [Configurable](#configurable)
    - [Command-in-command](#command-in-command)
- [Light footprint](#light-footprint)
- [Usage](#usage)
    - [Verbosity](#verbosity)
    - [Code example](#code-example)
- [License](#license)

# What does it do?

**ConsoleLogg provides output messages for your artisan commands, between your shared code which is using the built-in
Laravel logger**

Typically, this requires a hacky solution, such as coupling your shared services with a console logger, or configuring
multiple driver channels.

tl;dr: Installing this package & using `logger()->critical("Message")`
or `Log::critical("Something horrible has happened")` in your shared service code, will:

- Yield `[critical] Something horrible has happened` when service is run in your artisan command
- or, log as per usual when used in your web environment

It works out of the box with zero configuration.

## Install

1. Install the package via Composer:

    ```shell script
   composer require devthis/console-logg
   
   # to optionally copy config
   php artisan vendor:publish --provider="DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider"
    ```

### Compatibility

| Compatible         | Laravel   | PHP       |
|--------------------|-----------|-----------|
| :heavy_check_mark: | 8.*       | PHP >=7.1 |
| :heavy_check_mark: | 7.*       | PHP >=7.1 |
| :heavy_check_mark: | 6.*       | PHP >=7.1 |
| :heavy_exclamation_mark: :soon: | > 5.6     | PHP >=7.1 |

#### Compatibility assurance

<details>
  <summary>Click to find out how we ensure compatibility</summary>

Compatibility is thoroughly tested using a combination of real world tests (functional), and unit tests

[ConsoleLogg test suite](/test) is run in isolation

- Using PHP 7.1, 7.2, 7.3,
  7.4, [each major version of Laravel is independently tested against](actions?query=workflow%3A"PHPUnit+suite")
- _Laravel 5.6, 5.7 & 5.8 are tested individually on top of this_

Unit tests ensure type-compatibility, expected behaviour is met & compatibility with each version of Laravel contracts

[TODO] Functional tests ensure real world expectations are through a real Laravel application

</details>

# Features

## Works out of the box

There are no traits, classes, interfaces that you need to use. ConsoleLogg does not require any custom code for usage.

It just works with your application using the logger in the typical ways

- [x] Autowiring DI (eg. LogInterface, LogManager, Logger)
- [x] Log Facade

## Zero config

ConsoleLogg service provider will be automatically added as a provider for your application.

However, if installed with `composer require --no-scripts` you will need to manually add the provider into
your `config/app.php`

```php
'providers' => [
    //...
    \DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider::class,
];
```

## Configurable

See [config/console-logg.php](config/console-logg.php) for the raw configuration file

### Filtering

@todo

### Extending

@todo

## Command-in-command

ConsoleLogg has (not yet) been tested for compatibility using artisan commands in a command
with [nested command calls](https://laravel.com/docs/8.x/artisan#calling-commands-from-other-commands)

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class MyConsoleApp extends Command
{
    protected $description = '';
    protected $signature = 'my:app';

    public function handle(): int
    {
        //other:command may invoke servies that use the Laravel Logger
        //these logs will still output to this current console
        $this->call('other:command');
        //...
        
        return 0;
    }
}
```

# Light footprint

- **Zero external dependencies** outside of Laravel contracts
- No external dependencies
- No memory leakage
    - One time use console logger is attached & detached alongside command execution
    - All references destroyed after command termination _(letting PHP Garbage Collection do its thing)_
- Service Provider lazily works only when running in console mode

# Usage

## Verbosity

Support is present for the default built-in `LOG_LEVEL` configuration

| Verbosity | Column header 2 |
| ---------------|----------------|
| -v | `emergency`, `alert`, `critical`, `error`, `warning`|
| -vv |`notice` + all of above |
| -vvv | `info` + all of above |

`debug` level logs will only be present when `APP_DEBUG=1` & `-vvv` is used

## Code example

> :warning: **REMINDER:** n-o-t-h-i-n-g is special about this following code example
>
> There are no traits, interfaces, or classes/dependencies that are involved to use ConsoleLogg once it's installed.

**Example Service**

```php
namespace App\Service;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class MyExampleService {
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function doSomethingCool(): void
    {
        // using Laravel's logger with DI/autowiring
        $this->logger->debug("A message that should have value when output to your application in general");
        
        // Facade
        Log::info("or just the facade if you love magic");
        
        // Helper function
        logger()->notice("or this weird helper function I guess");
        
        // ... <imaginary useful code here>
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
not-root@linux:~/MyProject$ php artisan something -vv
[debug] A message that should have value when output to your application in general
[info] or just the facade if you love magic
[notice] or this weird helper function I guess
```

## License

Laravel ConsoleLogg is open-sourced software licensed under the [MIT license](LICENSE.md).
