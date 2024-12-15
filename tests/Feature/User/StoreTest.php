<?php

use App\Models\User;

it('should create a new user', function () {
    $user = User::factory()->make()->toArray();

    $response = $this->postJson('/api/users', [
        'full_name' => $user['full_name'],
        'document'  => $user['document'],
        'email'     => $user['email'],
        'password'  => 'secret',
        'user_type' => $user['user_type'],
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', [
        'document' => $user['document'],
        'email'    => $user['email'],
    ]);
});

describe('validations', function () {
    it('should not create a user with the same email or document', function () {
        $user = User::factory()->create();

        $response = $this->postJson('/api/users', [
            'full_name' => $user->full_name,
            'document'  => $user->document,
            'email'     => $user->email,
            'password'  => 'secret',
            'user_type' => $user->user_type,
        ]);

        $response->assertStatus(422);
    });

});
