<?php

namespace App\Helpers;

class ApiResponse 
{

    public static function SendResponse($code = 200, $msg = null, $data = null)
    {
        $repsonse = [
            'status'        => $code,
            'message'       => $msg,
            'data'          => $data,
        ];

        return response()->json($repsonse, $code);
    }

}