<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\{JsonResponse, Request};

class UnauthorizedTransactionException extends Exception
{
    public function render(Request $request): JsonResponse
    {

        return response()->json([
            'status_code' => 400,
            'message'     => $this->message ? $this->message : 'Unauthorized transaction.',
            'timestamp'   => date('Y-m-d H:i:s'),
            'method'      => request()->method(),
            'path'        => request()->path(),
        ], 400);

        return parent::render($request);
    }
}
