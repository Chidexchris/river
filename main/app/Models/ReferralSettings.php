<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'referral_settings';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
