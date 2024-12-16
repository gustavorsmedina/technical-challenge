<?php

use App\Models\Wallet;
use App\Models\User;

it('should list wallet with id that was searched', function () {
    $user = User::factory()->create();

    $wallet = Wallet::factory()->create([
        'user_id' => $user->id
    ]);

    $id   = $wallet->id;

    $response = $this->get("/api/wallets/$id");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $id,
    ]);
});

it('should return 404 if the wallet doesnt exist', function () {
    $response = $this->get('/api/wallets/1');

    $response->assertStatus(404);
});
