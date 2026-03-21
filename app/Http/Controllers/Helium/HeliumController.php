<?php

namespace App\Http\Controllers\Helium;

use App\Http\Controllers\Controller;

class HeliumController extends Controller
{
    public function dashboard(){
        return view('helium.dashboard');
    }
}
