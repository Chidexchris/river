<?php

namespace App\Http\Requests;

use App\Lib\FormProcessor;
use App\Models\WireTransferSettings;
use Closure;
use HTMLPurifier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class WireTransferSettingsRequest extends FormRequest
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
        return Gate::allows('update', WireTransferSettings::class);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'instruction' => (new HTMLPurifier)->purify($this->input('instruction')),
        ]);

        $formGenerator = $this->input('form_generator') ?? [];

        $this->verifyEssentialFormFields($formGenerator['form_label'] ?? []);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge([
            'per_transaction_min_amount'     => 'bail|required|numeric|gt:0',
            'per_transaction_max_amount'     => 'bail|required|numeric|gt:per_transaction_min_amount',
            'daily_transaction_max_amount'   => 'bail|required|numeric|gt:per_transaction_max_amount',
            'daily_transaction_limit'        => 'bail|required|integer|gt:0',
            'monthly_transaction_max_amount' => 'bail|required|numeric|gt:daily_transaction_max_amount',
            'monthly_transaction_limit'      => 'bail|required|integer|gt:daily_transaction_limit',
            'fixed_charge'                   => 'bail|required_if:percentage_charge,null|nullable|numeric|gte:0',
            'percentage_charge'              => 'bail|required_if:fixed_charge,null|nullable|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
            'instruction'                    => [
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
            'per_transaction_max_amount.gt'     => 'The per transaction max amount must be greater than per transaction min amount.',
            'daily_transaction_max_amount.gt'   => 'The daily transaction max amount must be greater than per transaction max amount.',
            'monthly_transaction_max_amount.gt' => 'The monthly transaction max amount must be greater than daily transaction max amount.',
            'monthly_transaction_limit.gt'      => 'The monthly transaction limit must be greater than daily transaction limit.',
            'percentage_charge.regex'           => 'The percentage charge must take only two decimal places',
        ], $this->formProcessorValidation['messages']);
    }

    /**
     * Verify essential form fields.
     *
     * @param array $formLabels
     * @return void
     * @throws ValidationException
     */
    private function verifyEssentialFormFields(array $formLabels): void
    {
        $requiredFields = ['Account Name', 'Account Number'];
        $missingFields  = array_diff($requiredFields, $formLabels);

        if (!empty($missingFields)) {
            throw ValidationException::withMessages([
                'The following required fields are missing: "' . implode(', ', $missingFields) . '" in form configuration.'
            ]);
        }
    }
}
