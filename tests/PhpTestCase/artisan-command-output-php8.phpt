--TEST--
artisan command log output without verbosity
----SKIPIF--
<?php if(version_compare(PHP_VERSION, '8.0.0', '<')) print 'skipped'; ?>
--FILE--
<?php declare(strict_types=1);
$_SERVER['argv'][0] = 'artisan';
$_SERVER['argv'][1] = 'console-logg:test';
$_SERVER['argv'][2] = '-vvv';

require __DIR__ . '/../../../laravel-app/artisan';

--EXPECT--
[emergency] nice
[info] and
[alert] cool
