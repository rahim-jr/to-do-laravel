<?php

namespace App\Notifications;

use App\Models\todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class to_do_reminder_notification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public todo $todo) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Caution: Reminder, task due in 3 days')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your task '{$this->todo->title}' is due in 3 days.")
            ->line('Please ensure it is completed before the due date.')
            ->action('View Todo', url('/todos'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'todo_id' => $this->todo->id,
            'title' => $this->todo->title,
        ];
    }
}
