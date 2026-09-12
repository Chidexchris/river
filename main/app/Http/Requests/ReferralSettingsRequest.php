<?php

namespace App\Http\Requests;

use App\Models\ReferralSettings;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ReferralSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', ReferralSettings::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'referral_commission_count' => 'required|integer|gt:0',
            'percentage'                => 'sometimes|required|array|min:1',
            'percentage.*'              => 'bail|sometimes|required|numeric|gt:0|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'percentage.*.required' => 'Each commission percentage field is required',
            'percentage.*.numeric'  => 'Each commission percentage must be a number',
            'percentage.*.gt'       => 'Each commission percentage must be greater than 0',
            'percentage.*.regex'    => 'Each commission percentage must take only two decimal places',
        ];
    }
}
