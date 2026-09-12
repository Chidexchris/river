<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WireTransferSettings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'per_transaction_min_amount',
        'per_transaction_max_amount',
        'daily_transaction_max_amount',
        'daily_transaction_limit',
        'monthly_transaction_max_amount',
        'monthly_transaction_limit',
        'fixed_charge',
        'percentage_charge',
        'instruction',
        'form_id',
    ];

    /**
     * Get the form that owns the wire-transfer-settings.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }
}
