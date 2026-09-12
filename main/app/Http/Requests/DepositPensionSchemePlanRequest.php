<?php

namespace App\Http\Requests;

use App\Models\DepositPensionSchemePlan;
use HTMLPurifier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DepositPensionSchemePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('depositPensionSchemePlan')) {
            return Gate::allows('create', DepositPensionSchemePlan::class);
        } else {
            return Gate::allows('edit', DepositPensionSchemePlan::class);
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'icon' => (new HTMLPurifier)->purify($this->input('icon')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $planId = $this->route('depositPensionSchemePlan')?->id ?? null;

        return [
            'name'                 => "required|string|max:40|unique:deposit_pension_scheme_plans,name,$planId",
            'icon'                 => 'required|string|max:255',
            'per_installment'      => 'required|numeric|gt:0',
            'installment_interval' => 'required|integer|gt:0',
            'total_installment'    => 'required|integer|gt:0',
            'interest_rate'        => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'delay_duration'       => 'required|integer|gt:0',
            'fixed_charge'         => 'nullable|required_if:percentage_charge,null|numeric|gte:0',
            'percentage_charge'    => 'nullable|required_if:fixed_charge,null|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'percentage_charge.regex' => 'The percentage charge must take only two decimal places',
        ];
    }
}
