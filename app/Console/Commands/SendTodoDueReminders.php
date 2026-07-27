<?php

namespace App\Console\Commands;

use App\Models\todo;
use App\Notifications\to_do_reminder_notification;
use Illuminate\Console\Command;

class SendTodoDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todos:send-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notifications for pending todos due in 3 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $todos = todo::with('user')
            ->where('is_completed', false)
            ->whereNull('reminder_sent_at')
            ->whereNotNull('due_at')
            ->where('due_at', '<=', now()->addDays(3))
            ->where('due_at', '>', now())
            ->get();

        $count = 0;

        foreach ($todos as $todoItem) {
            if ($todoItem->user) {
                $todoItem->user->notify(new to_do_reminder_notification($todoItem));
                $todoItem->update(['reminder_sent_at' => now()]);
                $count++;
            }
        }

        $this->info("Sent {$count} todo reminder notification(s).");
    }
}
