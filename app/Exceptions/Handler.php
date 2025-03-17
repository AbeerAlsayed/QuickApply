<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function register()
    {
        $this->renderable(function (\App\Exceptions\ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        });

        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => 'Model not found: ' . $e->getMessage(),
            ], 404);
        });
    }
}
