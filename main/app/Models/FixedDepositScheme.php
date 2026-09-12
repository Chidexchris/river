<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FixedDepositScheme extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan_name',
        'scheme_code',
        'interest_rate',
        'deposit_amount',
        'interest_payout_interval',
        'per_installment',
        'profit_amount',
        'next_installment_date',
        'locked_until',
        'transfer_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'next_installment_date' => 'datetime',
        'locked_until'          => 'datetime',
    ];

    /**
     * Scope a query to only include running fds.
     */
    public function scopeRunning(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::FDS_RUNNING);
    }

    /**
     * Scope a query to only include closed fds.
     */
    public function scopeClosed(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::FDS_CLOSED);
    }

    /**
     * Get the user that owns the fds.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the fixed-deposit-scheme's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::FDS_CLOSED => '<span class="badge badge--secondary">' . trans('Closed') . '</span>',
                    default                  => '<span class="badge badge--success">' . trans('Running') . '</span>',
                };
            },
        );
    }
}
