<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthorizerService
{
    public function authorize()
    {

        $request = Http::get('https://util.devi.tools/api/v2/authorize');

        return $request['data']['authorization'];
    }
}
