<?php

declare(strict_types=1);

namespace App;

use Psr\Log\LoggerInterface;

class ServiceThatLoggs
{
    public function __construct(LoggerInterface $logger)
    {
        $logger->emergency('nice');
        $logger->info('and');
        $logger->alert('cool');
    }
}
