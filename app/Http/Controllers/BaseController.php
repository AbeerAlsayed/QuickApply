<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    // رد نجاح
    public function sendSuccess($result,$message = 'Operation successful')
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $result,
        ], 200);
    }

    // رد خطأ
    public function sendError($message, array $errors = [],$code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
