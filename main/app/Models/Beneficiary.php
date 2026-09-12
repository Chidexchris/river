<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Beneficiary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'beneficiaryable_id',
        'beneficiaryable_type',
        'details',
    ];

    /**
     * Get the money-transfers for the beneficiary.
     */
    public function moneyTransfers(): HasMany
    {
        return $this->hasMany(MoneyTransfer::class, 'beneficiary_id', 'id');
    }

    /**
     * Get the parent beneficiaryable model (user or other_bank).
     */
    public function beneficiaryable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Converts the stored 'details' value into an array if it is not already.
     * Iterates through the details array and encrypts the 'value' of any item
     * that has a 'type' of 'file'.
     */
    protected function details(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $details = is_array($value) ? $value : json_decode($value, true);

                foreach ($details as &$item) {
                    if (isset($item['type']) && $item['type'] === 'file' && !is_null($item['value'])) {
                        $item['value'] = encrypt($item['value']);
                    }
                }

                unset($item);

                return $details;
            },
        );
    }
}
