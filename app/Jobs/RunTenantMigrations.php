<?php

namespace App\Jobs;

use App\Models\Central\Tenant;
use App\Services\TenantDatabaseManager;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RunTenantMigrations implements ShouldQueue
{
    use Queueable;

  public function __construct(public Tenant $tenant) {}

    public function handle()
    {
       TenantDatabaseManager::connect($this->tenant);

        // remove from memory
        \DB::purge('tenant');
        \DB::reconnect('tenant');

        // run the migrations
        Artisan::call('migrate', [
            '--database' => 'tenant', // config/database.php db name
            '--path'     => 'database/migrations/tenant', // base_path 
            '--force'    => true,
        ]);
    }
}
