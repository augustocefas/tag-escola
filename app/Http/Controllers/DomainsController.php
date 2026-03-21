<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainsController extends Controller
{
    public static function domainExist(string $domain){
        $tenant = \Stancl\Tenancy\Database\Models\Domain::where('domain', $domain)->first();
        return $tenant ? true : false;
    }
}
