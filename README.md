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
    - [Artisan serve supported](#artisan-serve-supported)
    - [Literally Effortless](#literally-effortless)
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

With ConsoleLogg you can have logs for your artisan commands, and behave as usual with http/controllers.

**Use your Laravel logger throughout your application shared services as usual**

- _Dependency Injection/autowiring_ `LoggerInterface $logger` & `$logger->debug("yeet")`
- `logger()->critical("Send help")`
- `Log::alert("She find pictures in my email")`
- `Log::info("Found <X> to be processed")`

```
php artisan my:command -vvv
[debug] yeet
[critical] Send help
[alert] She find pictures in my email
[info] Found <X> to be processed
```

---
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

**PHP 8 compatibility is currently being tested**

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

---
# Features

## Artisan serve supported
Logger output will be shown in your local development server console.

## Literally Effortless

Your application **will not be coupled** with ConsoleLogg.

There are no traits, classes, interfaces that you need to use. ConsoleLogg does not require any custom code, it just works.

The ConsoleLog Service Provider should be automatically added to your app, but if it hasn't, you can add it yourself to `config/app.php`
```php
// generally only required when you have composer intalled with --no-scripts

'providers' => [
    //...
    \DevThis\ConsoleLogg\Providers\ConsoleLoggServiceProvider::class,
];
```

## Settings

See [config/console-logg.php](config/console-logg.php) for the raw configuration file

### Filtering

> default = `false`

If you choose to enable filtering, logs will only be output to artisan console commands if they have the context property `logg` set to `true`

eg.

```php
logger()->info("Informative message #1", ['logg' => true]);
logger()->alert("Nice one");
```

With these logs being invoked by your artisan command, only `[info] Informative message #1` will be output

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

---
# Light footprint

- **Zero external dependencies** outside of Laravel contracts
- No memory leakage (_needs validation/tests_)
    - One time use console logger is attached & detached alongside command execution
    - All references destroyed after command termination _(letting PHP Garbage Collection do its thing)_
- Service Provider lazily works only when running in console mode

---
# Usage

## Verbosity

Verbosity is optionally controlled by either using `-v` arguments when running artisan.

This is not behaviour set by ConsoleLogg, it is defined in combination of [Laravel](https://github.com/laravel/framework/blob/8.x/src/Illuminate/Console/Concerns/InteractsWithIO.php#L43) & [Symfony](https://github.com/symfony/console/blob/5.x/Logger/ConsoleLogger.php#L33)

_ConsoleLogg may provide configuration  for this in the future, if demand is apparent_

| Verbosity | Level |
| ---------------|----------------|
| _default_ | `emergency`, `alert`, `critical`, `error`, `warning`|
| -v | `notice` + all of above |
| -vv |`info` + all of above |
| -vvv | `debug` + all of above |

# Examples

### Running artisan
<details>
  <summary>View example usage</summary>

## Example #1 - SQL query logging

There are several guides/answers on the internet that enable you to send all SQL queries to your configured Logger.

With ConsoleLogg installed this means 

Links (in no order):
- [Code Briefly - Seeing all SQL queries executed with your artisan command](https://codebriefly.com/how-to-log-all-sql-queries-in-laravel/)
- [StackOverflow - Laravel 5.3 - How to log all queries on a page?](https://stackoverflow.com/a/43137632)
- [Larvel Tricks - Real-time log of Eloquent SQL queries](https://laravel-tricks.com/tricks/real-time-log-of-eloquent-sql-queries)

## Example #2 - Raw code

> :warning: **REMINDER:** n-o-t-h-i-n-g is special about this following code example
>
> There are no traits, interfaces, or classes/dependencies that are involved to use ConsoleLogg once it's installed.

### Souce code for example service
<details>
  <summary>Source of App\Service\MyExampleService</summary>

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
</details>

### Source code for Console Application
<details>
  <summary>Source of App\Console\Commands\ExampleConsole</summary>
    
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
</details>

### Running artisan
<details>
  <summary>Artisan command output</summary>

```bash
not-root@linux:~/MyProject$ php artisan something -vv
[debug] A message that should have value when output to your application in general
[info] or just the facade if you love magic
[notice] or this weird helper function I guess
```

</details>
</details>


---
# License

Laravel ConsoleLogg is open-sourced software licensed under the [MIT license](LICENSE.md).
