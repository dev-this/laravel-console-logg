--TEST--
artisan command log output without verbosity
--FILE--
<?php declare(strict_types=1);
$_SERVER['argv'][0] = 'artisan';
$_SERVER['argv'][1] = 'console-logg:test';
$_SERVER['argv'][2] = '-vvv';

require __DIR__ . '/../../../laravel-app/artisan';

--EXPECT--
#!/usr/bin/env php
[emergency] nice
[alert] cool
