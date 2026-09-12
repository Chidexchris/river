<?php

namespace App\Http\Requests;

use App\Lib\FormProcessor;
use App\Models\Beneficiary;
use App\Models\OtherBank;
use App\Rules\UniqueBeneficiaryAccount;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BeneficiaryRequest extends FormRequest
{
    protected ?Beneficiary $beneficiary = null;

    /**
     * Resolve the beneficiary once and store it.
     */
    protected function resolveBeneficiary(): void
    {
        if ($this->route('beneficiary')) $this->beneficiary = $this->route('beneficiary');
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->resolveBeneficiary();

        return !$this->beneficiary || $this->beneficiary->user_id === auth('web')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Predefined validation rules
        $rules = [
            'beneficiary_type' => 'required|string|in:own_bank,other_bank',
            'other_bank'       => 'sometimes|required|integer',
            'account_number'   => ['required', 'string', new UniqueBeneficiaryAccount(auth('web')->id(), $this->beneficiary?->id)],
            'account_name'     => 'sometimes|required|string|max:255',
            'short_name'       => 'required|string|max:40',
        ];

        // Dynamically add rules if 'other_bank' is filled
        if ($this->filled('other_bank')) {
            $otherBank = OtherBank::with('form')->active()->findOrFail($this->input('other_bank'));
            $formData  = $otherBank->form->form_data;

            // Get dynamic validation rules from the FormProcessor
            $dynamicRules = (new FormProcessor)->valueValidation($formData);

            // Exclude account_name, account_number, and short_name from dynamic rules
            $filteredDynamicRules = array_diff_key($dynamicRules, array_flip(['account_number', 'account_name', 'short_name']));

            // Merge dynamic rules with the predefined $rules array
            $rules = array_merge($rules, $filteredDynamicRules);
        }

        return $rules;
    }
}
