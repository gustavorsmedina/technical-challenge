<?php

use App\Models\User;

it('should delete user with success', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson("/api/users/{$user->id}");

    $response->assertStatus(204);
    $this->assertDatabaseHas('users', [
        'id'     => $user->id,
        'status' => 'inactive',
    ]);
});
