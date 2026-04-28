<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enum\MeetingScheduleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class MeetingSchedule extends Model
{
    use HasFactory;

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'school_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'created_by',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => MeetingScheduleStatus::class,
    ];

    /**
     * Apply global scope to restrict by session school when not on system main
     */
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            $sessionSchoolName = session('school_name');
            $envSchoolName = config('subdomain.system_main');

            if (is_null($sessionSchoolName) || is_null($envSchoolName)) {
                return;
            }

            if ($sessionSchoolName !== $envSchoolName) {
                $builder->where('school_id', session('school_id'));
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function targets()
    {
        return $this->hasMany(MeetingScheduleTarget::class);
    }

    /**
     * Compute `status` dynamically based on `start_time`/`end_time`,
     * except when the stored status is CANCELLED.
     * Returns a MeetingScheduleStatus enum instance for reads.
     */
    protected function status(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            // Determine stored status value (int)
            $stored = $value ?? ($attributes['status'] ?? null);

            $storedInt = is_int($stored) ? $stored : (int) ($stored ?? 0);

            // If explicitly cancelled, keep cancelled
            if ($storedInt === MeetingScheduleStatus::CANCELLED->value) {
                return MeetingScheduleStatus::CANCELLED;
            }

            // Try to compute based on start_time/end_time
            $start = isset($attributes['start_time']) && $attributes['start_time'] ? Carbon::parse($attributes['start_time']) : null;
            $end = isset($attributes['end_time']) && $attributes['end_time'] ? Carbon::parse($attributes['end_time']) : null;

            $now = now();

            if ($start && $start->greaterThan($now)) {
                return MeetingScheduleStatus::UPCOMING;
            }

            if ($end && $end->lessThan($now)) {
                return MeetingScheduleStatus::COMPLETED;
            }

            // Fallback to ONGOING if we have both times, otherwise return stored as enum
            if ($start || $end) {
                return MeetingScheduleStatus::ONGOING;
            }

            return MeetingScheduleStatus::from($storedInt ?: MeetingScheduleStatus::UPCOMING->value);
        });
    }
}
