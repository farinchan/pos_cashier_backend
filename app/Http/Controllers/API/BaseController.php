<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data'    => null
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function sendErrorValidation($validation = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => 'Validation Error.',
            'validation' => $validation,
            'data'    => [],
        ];

        return response()->json($response, $code);
    }

    public function sendResponseValidation($result, $message, $validation)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'validation' => $validation,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendResponseWithPagination($result, $message, Request $query)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'meta' => [
                'keyword' => $query->input('q'),
                'path' => $result->path(),
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'from' => $result->firstItem(),
                'to' => $result->lastItem(),
                'has_next_page' => $result->hasMorePages(),
                'has_prev_page' => $result->previousPageUrl() ? true : false,
            ],
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }
}
