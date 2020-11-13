<?php

declare(strict_types=1);

return [
    /**
     * When enabled, logs will only be output to the console application if the context
     *  contains the key 'logg' and the value equals true
     *
     * ```php
     *   Log::info('A really good thing to log', ['logg' => true]);
     * ```
     */
    'filtered' => false,
];
