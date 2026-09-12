<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Interfaces\HasInstallments;
use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class DepositPensionScheme extends Model implements HasInstallments
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
        'per_installment',
        'installment_interval',
        'total_installment',
        'given_installment',
        'total_deposit_amount',
        'interest_rate',
        'profit_amount',
        'maturity_amount',
        'delay_duration',
        'per_installment_late_fee',
        'total_late_fees',
        'late_fee_last_notified_at',
        'transfer_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'late_fee_last_notified_at' => 'datetime',
        'withdrawn_at'              => 'datetime',
    ];

    /**
     * Scope a query to only include running dps.
     */
    public function scopeRunning(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::DPS_RUNNING);
    }

    /**
     * Scope a query to only include matured dps.
     */
    public function scopeMatured(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::DPS_MATURED);
    }

    /**
     * Scope a query to only include closed dps.
     */
    public function scopeClosed(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::DPS_CLOSED);
    }

    /**
     * Get all the deposit-pension-scheme's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    /**
     * Get the deposit-pension-scheme's oldest installment.
     */
    public function oldestInstallment(): MorphOne
    {
        return $this->morphOne(Installment::class, 'installmentable')->oldestOfMany();
    }

    /**
     * Get the user that owns the dps.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::DPS_CLOSED  => '<span class="badge badge--secondary">' . trans('Closed') . '</span>',
                    ManageStatus::DPS_MATURED => '<span class="badge badge--info">' . trans('Matured') . '</span>',
                    default                   => '<span class="badge badge--success">' . trans('Running') . '</span>',
                };
            },
        );
    }

    public function getInstallmentStartDate(): ?Carbon
    {
        return $this->created_at;
    }

    public function getInstallmentInterval(): int
    {
        return $this->installment_interval;
    }

    public function getTotalInstallments(): int
    {
        return $this->total_installment - 1;
    }

    public function getInstallmentDate(): Carbon
    {
        return now();
    }
}
