<?php

namespace App\Traits;

trait ResponseTrait
{
    public function errorResponse($message, $code = 400)
    {
        return response()->json(['error' => [
            'message' => $message,
            'code' => $code
        ]], $code);
    }
}
