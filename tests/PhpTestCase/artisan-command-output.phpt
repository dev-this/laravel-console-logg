--TEST--
artisan command log output without verbosity
--FILE--
<?php declare(strict_types=1);
$_SERVER['argv'][0] = 'artisan';
$_SERVER['argv'][1] = 'console-logg:test';

if (version_compare(PHP_VERSION, '8.0.0', '<')) {
// PHPUnit later versions started to replace this header string
// Testing across multiple PHP versions means sometimes PHPUnit version will be older, and not have the str replace
// Hence why the output is here when PHP < 8.0
$out = fopen('php://output', 'w'); //output handler
fputs($out, "#!/usr/bin/env php\n");
fclose($out);
}

require __DIR__ . '/../../../laravel-app/artisan';

--EXPECT--
#!/usr/bin/env php
[emergency] nice
[alert] cool
