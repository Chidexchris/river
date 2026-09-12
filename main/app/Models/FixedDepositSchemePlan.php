<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;

class FixedDepositSchemePlan extends Model
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
        'interest_rate',
        'interest_payout_interval',
        'lock_in_period',
        'minimum_amount',
        'maximum_amount',
        'status',
    ];
}
