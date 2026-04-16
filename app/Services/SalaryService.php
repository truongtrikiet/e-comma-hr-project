<?php

namespace App\Services;

use App\Models\Salary;
use Illuminate\Support\Collection;

class SalaryService
{
    /**
     * Get the audit information for a salary.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Support\Collection
     */
    public function getSalaryAudits(Salary $salary): Collection
    {
        $salaryAudits = $salary->audits->map(function ($audit) {
            return [
                'old_value' => $audit->old_values,
                'new_value' => $audit->new_values,
                'sent_at' => $audit->created_at,
                'editor' => $audit->user,
            ];
        });

        $userAudits = $salary->user->audits->map(function ($audit) {
            return [
                'old_value' => $audit->old_values,
                'new_value' => $audit->new_values,
                'sent_at' => $audit->created_at,
                'editor' => $audit->user,
            ];
        });

        return $salaryAudits->merge($userAudits);
    }
}
