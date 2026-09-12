<?php

namespace App\Http\Requests;

use App\Models\FixedDepositSchemePlan;
use HTMLPurifier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class FixedDepositSchemePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('fixedDepositSchemePlan')) {
            return Gate::allows('create', FixedDepositSchemePlan::class);
        } else {
            return Gate::allows('edit', FixedDepositSchemePlan::class);
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
        $planId = $this->route('fixedDepositSchemePlan')?->id ?? null;

        return [
            'name'                     => "required|string|max:40|unique:fixed_deposit_scheme_plans,name,$planId",
            'icon'                     => 'required|string|max:255',
            'interest_rate'            => 'required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'interest_payout_interval' => 'required|integer|gt:0',
            'lock_in_period'           => 'required|integer|gt:0',
            'minimum_amount'           => 'required|numeric|gt:0',
            'maximum_amount'           => 'required|numeric|gt:minimum_amount',
        ];
    }
}
