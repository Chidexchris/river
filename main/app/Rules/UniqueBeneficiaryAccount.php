<?php

namespace App\Rules;

use App\Models\Beneficiary;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class UniqueBeneficiaryAccount implements ValidationRule
{
    public function __construct(protected int $userId, protected ?int $beneficiaryId = null)
    {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $accountExists = Beneficiary::where('user_id', $this->userId)
            ->where(function (Builder $query) use ($value) {
                $query->whereJsonContains('details', ['account_number' => $value])
                    ->orWhere(function (Builder $q) use ($value) {
                        $q->whereRaw('JSON_CONTAINS(details, ?)', [
                            json_encode(['value' => $value])
                        ]);
                    });
            })
            ->when($this->beneficiaryId, function (Builder $query) {
                $query->whereNot('id', $this->beneficiaryId);
            })
            ->exists();

        if ($accountExists) $fail('This :attribute is already added to your beneficiaries.');
    }
}
