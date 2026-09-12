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

class Loan extends Model implements HasInstallments
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
        'amount_requested',
        'form_data',
        'per_installment',
        'installment_interval',
        'total_installment',
        'given_installment',
        'delay_duration',
        'per_installment_late_fee',
        'total_late_fees',
        'late_fee_last_notified_at',
        'status',
        'approved_at',
        'admin_feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'late_fee_last_notified_at' => 'datetime',
        'approved_at'               => 'datetime',
    ];

    /**
     * Scope a query to only include rejected loans.
     */
    public function scopeRejected(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::LOAN_REJECTED);
    }

    /**
     * Scope a query to only include running loans.
     */
    public function scopeRunning(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::LOAN_RUNNING);
    }

    /**
     * Scope a query to only include paid loans.
     */
    public function scopePaid(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::LOAN_PAID);
    }

    /**
     * Scope a query to only include pending loans.
     */
    public function scopePending(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::LOAN_PENDING);
    }

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all the loan's installments.
     */
    public function installments(): MorphMany
    {
        return $this->morphMany(Installment::class, 'installmentable');
    }

    /**
     * Converts the stored 'form_data' value into an array if it is not already.
     * Iterates through the payload array and encrypts the 'value' of any item
     * that has a 'type' of 'file'.
     */
    protected function formData(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                $payload = is_array($value) ? $value : json_decode($value, true);

                foreach ($payload as &$item) {
                    if (isset($item['type']) && $item['type'] === 'file' && !is_null($item['value'])) {
                        $item['value'] = encrypt($item['value']);
                    }
                }

                unset($item);

                return $payload;
            },
        );
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::LOAN_REJECTED => '<span class="badge badge--danger">' . trans('Rejected') . '</span>',
                    ManageStatus::LOAN_RUNNING  => '<span class="badge badge--success">' . trans('Running') . '</span>',
                    ManageStatus::LOAN_PAID     => '<span class="badge badge--info">' . trans('Paid') . '</span>',
                    default                     => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                };
            },
        );
    }

    public function getInstallmentStartDate(): ?Carbon
    {
        return $this->approved_at;
    }

    public function getInstallmentInterval(): int
    {
        return $this->installment_interval;
    }

    public function getTotalInstallments(): int
    {
        return $this->total_installment;
    }

    public function getInstallmentDate(): Carbon
    {
        return now()->addDays($this->getInstallmentInterval());
    }
}
