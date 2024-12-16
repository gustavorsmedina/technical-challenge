<?php

use App\Models\{User, Wallet};

it('should create a transaction', function () {

    Http::fake([
        'https://util.devi.tools/api/v2/authorize' => Http::response([
            'data' => ['authorization' => true],
        ], 200),
    ]);

    $payer = User::factory()->state(['user_type' => 'common'])->create();
    $payee = User::factory()->create();

    Wallet::factory()->create([
        'user_id' => $payer->id,
        'balance' => 1000,
    ]);

    Wallet::factory()->create([
        'user_id' => $payee->id,
        'balance' => 0,
    ]);

    $response = $this->postJson('/api/transactions', [
        'payer' => $payer->id,
        'payee' => $payee->id,
        'value' => 100,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('transactions', [
        'payer'  => $payer->id,
        'payee'  => $payee->id,
        'value'  => 100,
        'status' => 'success',
    ]);

    $this->assertDatabaseHas('wallets', [
        'user_id' => $payer->id,
        'balance' => 900,
    ]);

    $this->assertDatabaseHas('wallets', [
        'user_id' => $payee->id,
        'balance' => 100,
    ]);
});

describe('validations', function () {
    it('should not create a transaction with a negative value', function () {
        $payer = User::factory()->state(['user_type' => 'common'])->create();
        $payee = User::factory()->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => -100,
        ]);

        $response->assertStatus(422);
    });

    it('should not create a transaction with a payer or payee not found', function () {
        $payer = User::factory()->state(['user_type' => 'common'])->create();
        $payee = User::factory()->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => 10,
            'payee' => 20,
            'value' => 100,
        ]);

        $response->assertStatus(404);
    });

    it('should not create a transaction with insufficient balance', function () {
        $payer = User::factory()->state(['user_type' => 'common'])->create();
        $payee = User::factory()->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 1001,
        ]);

        $response->assertStatus(422);
    });

    it('should not create a transaction with a merchant payer', function () {
        $payer = User::factory()->state(['user_type' => 'merchant'])->create();
        $payee = User::factory()->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 100,
        ]);

        $response->assertStatus(422);
    });

    it('should not create a transaction with an inactive payer', function () {
        $payer = User::factory()->state(['status' => 'inactive'])->create();
        $payee = User::factory()->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 100,
        ]);

        $response->assertStatus(400);
    });

    it('should not create a transaction with an inactive payee', function () {
        $payer = User::factory()->state(['user_type' => 'common'])->create();
        $payee = User::factory()->state(['status' => 'inactive'])->create();

        Wallet::factory()->create([
            'user_id' => $payer->id,
            'balance' => 1000,
        ]);

        Wallet::factory()->create([
            'user_id' => $payee->id,
            'balance' => 0,
        ]);

        $response = $this->postJson('/api/transactions', [
            'payer' => $payer->id,
            'payee' => $payee->id,
            'value' => 100,
        ]);

        $response->assertStatus(400);
    });
});
