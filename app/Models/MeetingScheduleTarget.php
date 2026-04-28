<?php

namespace App\Models;

use App\Enum\MeetingTargetType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingScheduleTarget extends Model
{
use HasFactory;

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'meeting_schedule_id',
        'target_type',
        'target_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'target_type' => MeetingTargetType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function meetingSchedule()
    {
        return $this->belongsTo(MeetingSchedule::class);
    }
}
