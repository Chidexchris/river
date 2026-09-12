<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPlan extends Model
{
    use Searchable, UniversalStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
        'minimum_amount',
        'maximum_amount',
        'installment_rate',
        'installment_interval',
        'total_installments',
        'instruction',
        'delay_duration',
        'fixed_charge',
        'percentage_charge',
        'form_id',
        'status',
    ];

    /**
     * Get the form that owns the loan plan.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }
}
