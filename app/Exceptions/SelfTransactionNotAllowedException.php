<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\{JsonResponse, Request};

class SelfTransactionNotAllowedException extends Exception
{
    public function render(Request $request): JsonResponse
    {

        return response()->json([
            'status_code' => 422,
            'message'     => $this->message ? $this->message : 'Self transaction is not allowed.',
            'timestamp'   => date('Y-m-d H:i:s'),
            'method'      => request()->method(),
            'path'        => request()->path(),
        ], 422);

        return parent::render($request);
    }
}
