<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\{JsonResponse, Request};

class TransactionNotFoundException extends Exception
{
    public function render(Request $request): JsonResponse
    {

        return response()->json([
            'status_code' => 404,
            'message'     => $this->message ? $this->message : 'Transaction not found.',
            'timestamp'   => date('Y-m-d H:i:s'),
            'method'      => request()->method(),
            'path'        => request()->path(),
        ], 404);

        return parent::render($request);
    }
}
