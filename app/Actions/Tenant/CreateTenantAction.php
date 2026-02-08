<?php

namespace App\Actions\Tenant;

use App\Models\Central\Tenant;
use App\Jobs\RunTenantMigrations;
use App\Services\TenantDatabaseService;

class CreateTenantAction
{
  public function execute($data)
  {
        $tenant = Tenant::create([
            'name' => $data['name'],
            'domain' => $data['domain'],
            'db_name' => 'tenant_' . strtolower($data['domain']),
            'db_username' => 'root',
            'db_password' => '',
        ]);

        TenantDatabaseService::create($tenant);

        RunTenantMigrations::dispatch($tenant);

        return $tenant;
    }
}
