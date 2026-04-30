<?php

namespace App\Http\Requests\Salary;

use App\Acl\Acl;
use App\Enum\ExpiredSalaryStatus;
use App\Enum\SalaryStatus;
use App\Rules\ValidAmount;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SALARY_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'school_id' => [
                'required',
                'integer',
            ],
            'gross_amount' => [
                'required', 
                'min:'.($this->tax_amount ?? 0), 
                'numeric', 
                new ValidAmount
            ],
            'tax_percent' => [
                'required',
                'numeric',
                new ValidAmount
            ],
            'tax_amount' => [
                'required',
                'numeric',
                new ValidAmount
            ],
            'net_amount' => [
                'required',
                'numeric',
                new ValidAmount
            ],
            'approved_at' => [
                'nullable',
            ],
            'effective_date' => [
                'nullable'
            ],
            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:effective_date',
            ],
            'status' => [
                'required',
                new Enum(ExpiredSalaryStatus::class),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $gross = $this->input('gross_amount', $this->input('amount'));
        $taxPercent = $this->input('tax_percent', $this->input('tax'));

        if (is_string($gross)) {
            $gross = str_replace(',', '', $gross);
        }
        if (is_string($taxPercent)) {
            $taxPercent = str_replace(',', '', $taxPercent);
        }

        $this->merge([
            'amount' => $gross,
            'tax' => $taxPercent,
        ]);
    }

    /**
     * Get notifications from Rules when there is an error.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function messages() {
        return [
            'amount.min' => 'Trường giá trị số tiền phải lớn hơn tiền thuế.',
        ];
    }
}
