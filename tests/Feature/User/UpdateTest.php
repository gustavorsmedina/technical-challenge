<?php

use App\Models\User;

it('should update a user successfully', function () {
    $user    = User::factory()->create();
    $newUser = User::factory()->make();

    $response = $this->putJson("/api/users/{$user->id}", [
        'full_name' => $newUser->full_name,
        'email'     => $newUser->email,
        'document'  => $newUser->document,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'full_name',
                'email',
                'document',
            ],
        ]);
});

describe('validations', function () {
    it('should not update a user with an existing email or document', function () {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", [
            'email'    => $user->email,
            'document' => $user->document,
        ]);

        $response->assertStatus(422);
    });
});
