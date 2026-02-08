<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Tenant\CreateTenantAction;
use Illuminate\Http\Request;
class TenantController
{
   public function store(Request $request, CreateTenantAction $action)
    {
        
        $action->execute($request->only('name', 'domain'));

        return response()->json(['message' => 'Tenant created']);
    }
}
