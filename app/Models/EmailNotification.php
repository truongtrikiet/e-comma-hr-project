<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailNotification
 *
 */
class EmailNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_notifiable_type',
        'email_notifiable_id',
        'template_id',
        'name',
        'subject',
        'content',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
