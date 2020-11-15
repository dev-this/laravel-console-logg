<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\ServiceThatLoggs;
use Illuminate\Console\Command;

class ArtisanCommand extends Command
{
    /**
     * The console command description.
     */
    protected $description = '';

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'console-logg:test';

    public function handle(ServiceThatLoggs $service): int
    {
        return 0;
    }
}
