<?php

namespace App\Traits;

use App\Models\EmailNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait EmailNotifiable
{
    /**
     * Get the model's notification.
     */
    public function emailNotification(): MorphOne
    {
        return $this->morphOne(EmailNotification::class, 'notifiable');
    }

    /**
     * Get all of the model's notifications.
     */
    public function emailNotifications(): MorphMany
    {
        return $this->morphMany(EmailNotification::class, 'notifiable');
    }
}
