<?php

namespace App\Http\Controllers;

abstract class Controller
{ public function sendJsonResponse($status = true, $message = null, $data = array(), $status_code = 200)
    {
        return response([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }


    // try catch errors goes here
    public function sendError(\Exception $error)
    {
        report($error);
        if (app()->environment(['local'])) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage(),
            ], 500);
        }
        return response([
            'status' => false,
            'message' => 'something went wrong',
        ], 500);
    }//
}
