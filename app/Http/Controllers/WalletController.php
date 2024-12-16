<?php

namespace App\Http\Controllers;

use App\Http\Requests\{WalletOperationRequest, WalletStoreRequest};
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function store(WalletStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $wallet = $this->walletService->createWallet($data);

        return response()->json(['message' => 'success', 'data' => $wallet], 201)
            ->header('Location', route('wallets.show', ['wallet' => $wallet->id]));
    }

    public function show(string $id): JsonResponse
    {
        $wallet = $this->walletService->getWallet($id);

        return response()->json($wallet, 200);
    }

    public function credit(WalletOperationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $wallet = $this->walletService->creditWallet($data);

        return response()->json(['status' => 'success', 'data' => $wallet], 200);
    }

    public function debit(WalletOperationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $wallet = $this->walletService->debitWallet($data);

        return response()->json(['status' => 'success', 'data' => $wallet], 200);
    }
}
