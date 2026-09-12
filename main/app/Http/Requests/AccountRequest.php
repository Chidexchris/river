<?php

namespace App\Http\Requests;

use App\Constants\ManageStatus;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    protected ?User $userAccount = null;

    /**
     * Resolve the user account once and store it.
     */
    protected function resolveUserAccount(): void
    {
        if ($this->route('account')) {
            $this->userAccount = User::where('account_number', $this->route('account'))->firstOrFail();
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->resolveUserAccount();

        $staff = auth('staff')->user();

        if (!$this->userAccount) {
            return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER;
        } else {
            return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER && $staff->id == $this->userAccount->staff_id;
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
            'username' => trim($this->input('username')),
            'email'    => trim($this->input('email')),
            'mobile'   => trim($this->input('mobile_code') . $this->input('mobile')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $countries    = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countries));
        $countryNames = implode(',', array_column($countries, 'country'));
        $dialCodes    = implode(',', array_column($countries, 'dial_code'));

        return [
            'image'        => [$this->userAccount ? 'nullable' : 'required', 'mimes:png,jpg,jpeg', 'max:2048'],
            'firstname'    => 'required|string|max:40',
            'lastname'     => 'required|string|max:40',
            'username'     => "required|string|min:6|max:40|regex:/^[a-z0-9_]+$/|unique:users,username,{$this->userAccount?->id}",
            'email'        => "required|email:rfc,dns|max:40|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/|unique:users,email,{$this->userAccount?->id}",
            'country_name' => "required|string|in:$countryNames",
            'country_code' => "required|string|in:$countryCodes",
            'mobile'       => "required|string|max:40|regex:/^([0-9]*)$/|unique:users,mobile,{$this->userAccount?->id}",
            'mobile_code'  => "required|integer|in:$dialCodes",
            'address'      => 'required|string',
            'city'         => 'required|string',
            'state'        => 'nullable|string',
            'zip_code'     => 'required|string|max:10',
            'ref_by'       => 'sometimes|nullable|string|exists:users,account_number',
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
            'firstname.required' => 'The first name field is required.',
            'firstname.string'   => 'The first name must be a string.',
            'firstname.max'      => 'The first name must not be greater than :max characters.',
            'username.regex'     => 'The username may only contain lowercase letters, numbers, and underscores.',
            'email.regex'        => 'The email must contain only lowercase letters, numbers, and valid email characters.',
            'lastname.required'  => 'The last name field is required.',
            'lastname.string'    => 'The last name must be a string.',
            'lastname.max'       => 'The last name must not be greater than :max characters.',
            'ref_by.string'      => 'The referrer must be a string.',
            'ref_by.exists'      => 'The selected referrer is invalid.',
        ];
    }
}
