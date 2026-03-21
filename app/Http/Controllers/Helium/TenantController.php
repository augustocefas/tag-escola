<?php

    namespace App\Http\Controllers\Helium;

    use App\Models\Tenant;

class TenantController extends HeliumController
{

    public function index(){
        $tenants = Tenant::with('domains')->get();
        return view('helium.tenant.index', compact('tenants'));
    }

}
