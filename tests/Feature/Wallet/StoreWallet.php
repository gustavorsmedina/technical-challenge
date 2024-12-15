<?php

use App\Models\User;

it('should create a wallet successfully', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('wallets.store'), [
        'user_id' => $user->id,
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('wallets', [
        'user_id' => $user->id,
    ]);
});

it('should not create a wallet for a inactive user', function () {
    $user = User::factory()->state(['status' => 'inactive'])->create();

    $response = $this->postJson(route('wallets.store'), [
        'user_id' => $user->id,
    ]);

    $response->assertStatus(400);
    $this->assertDatabaseCount('wallets', 0);
});
