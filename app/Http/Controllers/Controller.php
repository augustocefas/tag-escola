<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __construct()
    {
        //
    }

    public function success($data = [], $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($data = [], $message = 'Error', $code = 401){
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
