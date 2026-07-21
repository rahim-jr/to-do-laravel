<?php

use App\Models\tags;
use App\Models\todo;
use App\Models\User;

test('authenticated user can view todo index', function () {
    $user = User::factory()->create();
    todo::factory()->count(3)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('todos.index'));

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

test('authenticated user can create a todo', function () {
    $user = User::factory()->create();
    $tag = tags::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('todos.store'), [
            'title' => 'New Test Todo',
            'description' => 'Todo Description',
            'is_completed' => false,
            'tags' => [$tag->id],
        ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'New Test Todo');

    $this->assertDatabaseHas('todos', [
        'user_id' => $user->id,
        'title' => 'New Test Todo',
    ]);
});

test('authenticated user can view their todo details', function () {
    $user = User::factory()->create();
    $todo = todo::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('todos.show', $todo));

    $response->assertOk()
        ->assertJsonPath('data.id', $todo->id);
});

test('authenticated user cannot view another user todo', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $todo = todo::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($user)
        ->getJson(route('todos.show', $todo));

    $response->assertForbidden();
});

test('authenticated user can update their todo', function () {
    $user = User::factory()->create();
    $todo = todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Old Title',
    ]);

    $response = $this->actingAs($user)
        ->putJson(route('todos.update', $todo), [
            'title' => 'Updated Title',
            'is_completed' => true,
        ]);

    $response->assertOk()
        ->assertJsonPath('data.title', 'Updated Title');

    $this->assertDatabaseHas('todos', [
        'id' => $todo->id,
        'title' => 'Updated Title',
        'is_completed' => 1,
    ]);
});

test('authenticated user can delete their todo', function () {
    $user = User::factory()->create();
    $todo = todo::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->deleteJson(route('todos.destroy', $todo));

    $response->assertOk();

    $this->assertDatabaseMissing('todos', [
        'id' => $todo->id,
    ]);
});

test('authenticated user can toggle todo completion', function () {
    $user = User::factory()->create();
    $todo = todo::factory()->create([
        'user_id' => $user->id,
        'is_completed' => false,
    ]);

    $response = $this->actingAs($user)
        ->patchJson(route('todos.toggle', $todo));

    $response->assertOk()
        ->assertJsonPath('data.is_completed', true);

    expect($todo->fresh()->is_completed)->toBeTrue();
});
