<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use UniversalStatus, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'method_id',
        'user_id',
        'branch_id',
        'staff_id',
        'amount',
        'currency',
        'rate',
        'charge',
        'trx',
        'final_amount',
        'after_charge',
        'withdraw_information',
        'status',
        'admin_feedback',
    ];

    protected $casts = [
        'withdraw_information' => 'object',
    ];

    protected $hidden = [
        'withdraw_information',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(WithdrawMethod::class, 'method_id');
    }

    /**
     * Get the staff that owns the withdrawal.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    /**
     * Get the branch that owns the withdrawal.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_PENDING);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_CANCEL);
    }

    public function scopeDone($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_SUCCESS);
    }

    public function scopeIndex($query)
    {
        return $query->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function scopeInitiate($query)
    {
        return $query->where('status', ManageStatus::PAYMENT_INITIATE);
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::PAYMENT_PENDING => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                    ManageStatus::PAYMENT_SUCCESS => '<span class="badge badge--success">' . trans('Done') . '</span>',
                    ManageStatus::PAYMENT_CANCEL  => '<span class="badge badge--danger">' . trans('Cancelled') . '</span>',
                    default                       => '<span class="badge badge--secondary">' . trans('Initiated') . '</span>',
                };
            },
        );
    }
}
