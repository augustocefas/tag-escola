<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Tenant};

class TenantController extends Controller
{
    public function index(){
        $tenants = Tenant::with('Domains')->get();
        return $this->success($tenants);
    }
    public function store(Request $request){
        $defaultHost = env('TENANT_URL', '.localhost');
        if(DomainsController::domainExist($request->input('domain'))){
            return response()->json(['error' => 'Domain already exists'], 400);
        }
        $tenant = Tenant::create();
        $domain_tenant = ($request->input('full_url')) ? $request->input('full_url') : $request->input('domain').$defaultHost;
        $tenant->domains()->create(['domain' => $domain_tenant]);
        if(isset($tenant)){
            return $this->success($tenant);
        }
        return response()->json(['error' => 'Failed to create tenant domain'], 500);
    }

    private function createTenantInitialConfig(){

    }
}
