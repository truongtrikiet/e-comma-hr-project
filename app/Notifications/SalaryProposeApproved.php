<?php

namespace App\Notifications;

use App\Models\SalaryPropose;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SalaryProposeApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected SalaryPropose $salaryPropose,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        $user = $this->salaryPropose->user;

        return [
            'salary_propose_id' => $this->salaryPropose->id,
            'message' => "{$user->name} has submitted a salary proposal.",
            'action_url' => route(
                'hr.salary-propose.show',
                $this->salaryPropose->id
            ),
        ];
    }
}
