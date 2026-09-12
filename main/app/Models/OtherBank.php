<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OtherBank extends Model
{
    use Searchable, UniversalStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'per_transaction_min_amount',
        'per_transaction_max_amount',
        'daily_transaction_max_amount',
        'daily_transaction_limit',
        'monthly_transaction_max_amount',
        'monthly_transaction_limit',
        'fixed_charge',
        'percentage_charge',
        'processing_time',
        'instruction',
        'form_id',
        'status',
    ];

    /**
     * Get the form that owns the other-bank.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    /**
     * Get all the other-bank's beneficiaries.
     */
    public function beneficiaries(): MorphMany
    {
        return $this->morphMany(Beneficiary::class, 'beneficiaryable');
    }
}
