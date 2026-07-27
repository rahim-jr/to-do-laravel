<?php

use App\Models\todo;
use App\Models\User;
use App\Notifications\to_do_reminder_notification;
use Illuminate\Support\Facades\Notification;

test('due reminders command sends notification for pending todo due within 3 days', function () {
    Notification::fake();

    $user = User::factory()->create();
    $dueTodo = todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Important Meeting Preparation',
        'is_completed' => false,
        'due_at' => now()->addDays(2),
        'reminder_set_at' => null,
    ]);

    $this->artisan('todos:send-due-reminders')
        ->expectsOutput('Sent 1 todo reminder notification(s).')
        ->assertExitCode(0);

    Notification::assertSentTo(
        $user,
        to_do_reminder_notification::class,
        function ($notification) use ($dueTodo) {
            return $notification->todo->id === $dueTodo->id;
        }
    );

    expect($dueTodo->fresh()->reminder_set_at)->not()->toBeNull();
});

test('due reminders command skips completed todos or todos with existing reminder', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Already completed
    todo::factory()->create([
        'user_id' => $user->id,
        'is_completed' => true,
        'due_at' => now()->addDays(2),
        'reminder_set_at' => null,
    ]);

    // Already sent reminder
    todo::factory()->create([
        'user_id' => $user->id,
        'is_completed' => false,
        'due_at' => now()->addDays(2),
        'reminder_set_at' => now()->subHour(),
    ]);

    $this->artisan('todos:send-due-reminders')
        ->expectsOutput('Sent 0 todo reminder notification(s).')
        ->assertExitCode(0);

    Notification::assertNothingSent();
});

test('updating due_at resets reminder_set_at allowing a new reminder notification', function () {
    $user = User::factory()->create();
    $todo = todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Project milestone',
        'due_at' => now()->addDays(1),
        'reminder_set_at' => now()->subHours(2),
    ]);

    $response = $this->actingAs($user)
        ->put(route('todos.update', $todo), [
            'title' => 'Project milestone',
            'due_at' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ]);

    $response->assertRedirect(route('todos.index'));

    expect($todo->fresh()->reminder_set_at)->toBeNull();
});
