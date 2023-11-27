<?php

namespace App\Http\Controllers;

class ApiResponseController extends Controller
{
    public function sendResponse($result, $message, $code = 200) {

        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, $code);
    }

    public function sendError($error, $errorMessages = [], $code = 404) {
        
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
