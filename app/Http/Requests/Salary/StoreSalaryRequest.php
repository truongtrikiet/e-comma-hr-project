<?php

namespace App\Http\Requests\Salary;

use App\Acl\Acl;
use App\Enum\SalaryStatus;
use App\Rules\AlphaSpaces;
use App\Rules\ValidAmount;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SALARY_ADD);
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
            'gross_amount' => [
                'required', 
                'min:'.$this->tax_amount, 
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
                'required',
            ],
            'effective_date' => [
                'required'
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
