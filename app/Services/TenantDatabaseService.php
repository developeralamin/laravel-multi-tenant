<?php

namespace App\Services;

use App\Models\Central\Tenant;
use Illuminate\Support\Facades\DB;

class TenantDatabaseService
{
    public static function create(Tenant $tenant)
    {
        DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->db_name}`");
    }
}
