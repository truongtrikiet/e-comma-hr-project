<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\MeetingSchedule;
use Closure;

class NoMeetingOverlap implements ValidationRule
{
    protected $ignoreId;
     protected $errorType;

    protected const ERROR_TYPE_OVERLAP = 1;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start = request()->input('start_time');
        $end = request()->input('end_time');

        if (!$start || !$end) {
            return;
        }

        $query = MeetingSchedule::query();
        $query->whereDate('start_time', date('Y-m-d', strtotime($start)))
              ->where('start_time', '<', $end)
              ->where('end_time', '>', $start);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

         if ($query->count() > 0) {
            $this->errorType = self::ERROR_TYPE_OVERLAP;
            $fail($this->messageError());
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function messageError()
    {
        switch ($this->errorType) {
            case self::ERROR_TYPE_OVERLAP:
            default:
                return __('validation.meeting_overlap');
        }
    }
}
