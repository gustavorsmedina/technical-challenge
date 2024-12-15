<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletStoreRequest;
use App\Services\WalletService;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function store(WalletStoreRequest $request)
    {
        $data = $request->validated();

        $wallet = $this->walletService->createWallet($data);

        return response()->json(['message' => 'success', 'data' => $wallet], 201);
    }

    public function show(string $id)
    {
        //
    }
}
