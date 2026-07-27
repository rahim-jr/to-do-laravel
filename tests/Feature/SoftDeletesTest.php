<?php

use App\Models\attachments;
use App\Models\tags;
use App\Models\todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can be soft deleted, restored, and force deleted', function () {
    $user = User::factory()->create();

    $user->delete();

    $this->assertSoftDeleted($user);
    expect(User::find($user->id))->toBeNull()
        ->and(User::withTrashed()->find($user->id))->not->toBeNull();

    $user->restore();

    $this->assertNotSoftDeleted($user);
    expect(User::find($user->id))->not->toBeNull();

    $user->forceDelete();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('todo can be soft deleted, restored, and force deleted', function () {
    $todo = todo::factory()->create();

    $todo->delete();

    $this->assertSoftDeleted($todo);
    expect(todo::find($todo->id))->toBeNull()
        ->and(todo::withTrashed()->find($todo->id))->not->toBeNull();

    $todo->restore();

    $this->assertNotSoftDeleted($todo);
    expect(todo::find($todo->id))->not->toBeNull();

    $todo->forceDelete();

    $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
});

test('tag can be soft deleted, restored, and force deleted', function () {
    $tag = tags::factory()->create();

    $tag->delete();

    $this->assertSoftDeleted($tag);
    expect(tags::find($tag->id))->toBeNull()
        ->and(tags::withTrashed()->find($tag->id))->not->toBeNull();

    $tag->restore();

    $this->assertNotSoftDeleted($tag);
    expect(tags::find($tag->id))->not->toBeNull();

    $tag->forceDelete();

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

test('attachment can be soft deleted, restored, and force deleted', function () {
    $attachment = attachments::factory()->create();

    $attachment->delete();

    $this->assertSoftDeleted($attachment);
    expect(attachments::find($attachment->id))->toBeNull()
        ->and(attachments::withTrashed()->find($attachment->id))->not->toBeNull();

    $attachment->restore();

    $this->assertNotSoftDeleted($attachment);
    expect(attachments::find($attachment->id))->not->toBeNull();

    $attachment->forceDelete();

    $this->assertDatabaseMissing('attachments', ['id' => $attachment->id]);
});
