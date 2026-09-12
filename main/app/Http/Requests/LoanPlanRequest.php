<?php

namespace App\Http\Requests;

use App\Lib\FormProcessor;
use App\Models\LoanPlan;
use Closure;
use HTMLPurifier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class LoanPlanRequest extends FormRequest
{
    protected array $formProcessorValidation;

    public function __construct()
    {
        parent::__construct();
        $this->formProcessorValidation = (new FormProcessor)->generatorValidation();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('plan')) {
            return Gate::allows('store', LoanPlan::class);
        } else {
            return Gate::allows('update', LoanPlan::class);
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
            'icon'        => (new HTMLPurifier)->purify($this->input('icon')),
            'instruction' => (new HTMLPurifier)->purify($this->input('instruction')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $planId = $this->route('plan')?->id ?? null;

        return array_merge([
            'name'                 => "required|string|max:40|unique:loan_plans,name,$planId",
            'icon'                 => 'required|string|max:255',
            'minimum_amount'       => 'required|numeric|gt:0',
            'maximum_amount'       => 'required|numeric|gt:minimum_amount',
            'installment_rate'     => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'installment_interval' => 'required|integer|gt:0',
            'total_installments'   => 'required|integer|gt:0',
            'instruction'          => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    $textLength = strlen(strip_tags($value));

                    if ($textLength < 10) {
                        $fail("The $attribute must have at least 10 characters of visible text.");
                    } elseif ($textLength > 64000) {
                        $fail("The $attribute must have at most 64,000 characters of visible text.");
                    }
                }
            ],
            'delay_duration'       => 'required|integer|gt:0',
            'fixed_charge'         => 'nullable|required_if:percentage_charge,null|numeric|gte:0',
            'percentage_charge'    => 'nullable|required_if:fixed_charge,null|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
        ], $this->formProcessorValidation['rules']);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return array_merge([
            'maximum_amount.gt'       => 'The maximum amount must be greater than the minimum amount.',
            'percentage_charge.regex' => 'The percentage charge must take only two decimal places',
        ], $this->formProcessorValidation['messages']);
    }
}
