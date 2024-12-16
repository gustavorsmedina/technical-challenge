<?php

use App\Models\{Transaction, User, Wallet};

it('should list transaction with id that was searched', function () {
    $payer = User::factory()->state(['user_type' => 'common'])->create();
    $payee = User::factory()->create();

    Wallet::factory()->create([
        'user_id' => $payer->id,
        'balance' => 10,
    ]);

    Wallet::factory()->create([
        'user_id' => $payee->id,
        'balance' => 0,
    ]);

    $transaction = Transaction::factory()->create([
        'payer' => $payer->id,
        'payee' => $payee->id,
        'value' => 10,
    ]);

    $id = $transaction->id;

    $response = $this->get("/api/transactions/$id");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $id,
    ]);
});
