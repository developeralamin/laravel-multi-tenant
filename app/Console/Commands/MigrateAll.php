<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateAll extends Command
{
   protected $signature = 'migrate:all {--fresh}';
    protected $description = 'Run root + central migrations safely';

    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Running migrate:fresh for root migrations...');
            Artisan::call('migrate:fresh');
        } else {
            $this->info('Running root migrations...');
            Artisan::call('migrate');
        }

        $this->info('Running central migrations...');
        Artisan::call('migrate', [
            '--path' => 'database/migrations/central',
            '--force' => true,
        ]);

        $this->info('All migrations completed successfully.');
    }
}
