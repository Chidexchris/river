<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Installment extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'installmentable_id',
        'installmentable_type',
        'installment_date',
        'given_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installment_date' => 'datetime',
        'given_at'         => 'datetime',
    ];

    /**
     * Get the parent installmentable model (dps or fds).
     */
    public function installmentable(): MorphTo
    {
        return $this->morphTo();
    }
}
