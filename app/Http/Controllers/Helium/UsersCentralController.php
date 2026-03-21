<?php

    namespace App\Http\Controllers\Helium;

    use App\Models\CentralUser;
    class UsersCentralController {
        public function index() {
            $data = CentralUser::all();
           return response()->json($data);

        }
    }
