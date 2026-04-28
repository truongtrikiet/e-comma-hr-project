<?php

namespace App\Notifications;

use App\Enum\SalaryStatus;
use App\Models\SalaryPropose;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SalaryProposeReviewed extends Notification
{
    use Queueable;

    protected SalaryStatus $status;

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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
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
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        $status = SalaryStatus::from($this->salaryPropose->status->value);

        return [
            'type' => 'salary_propose_reviewed',
            'salary_propose_id' => $this->salaryPropose->id,
            'status' => $status->value,
            'status_label' => SalaryStatus::getNameByValue($status->value),

            'action_url' => route(
                'staff.salary-propose.edit',
                $this->salaryPropose->id
            ),

            'message' => match ($status) {
                SalaryStatus::APPROVED => __('Your salary propose has been approved.'),
                SalaryStatus::REJECTED => __('Your salary propose has been rejected.'),
                default => __('Your salary propose status has been updated.'),
            },
        ];
    }
}
