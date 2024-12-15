<?php

use App\Models\User;

it('should list user with id that was searched', function () {
    $user = User::factory()->create();
    $id   = $user->id;

    $response = $this->get("/api/users/$id");

    $response->assertStatus(200);
    $response->assertJson([
        'id' => $id,
    ]);
});


it('should return 404 if the user doesnt exist', function () {
    $response = $this->get('/api/users/1');

    $response->assertStatus(404);
});

it('should list all users', function () {
    $users = User::factory()->count(2)->create();

    $response = $this->get('/api/users');

    $response->assertStatus(200);
    $response->assertJsonCount(2, 'data');
});

