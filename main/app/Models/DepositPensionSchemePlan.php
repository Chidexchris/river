<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class DepositPensionSchemePlan extends Model
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
        'per_installment',
        'installment_interval',
        'total_installment',
        'total_deposit_amount',
        'interest_rate',
        'profit_amount',
        'maturity_amount',
        'delay_duration',
        'fixed_charge',
        'percentage_charge',
        'status',
    ];
}
