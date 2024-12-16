<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionStoreRequest $request): JsonResponse
    {

        $data = $request->validated();

        $transaction = $this->transactionService->createTransaction($data);

        return response()->json(['status' => 'success', 'data' => $transaction], 201);

    }

    public function show(string $id): JsonResponse
    {
        $transaction = $this->transactionService->getTransaction($id);

        return response()->json($transaction, 200);
    }

}
