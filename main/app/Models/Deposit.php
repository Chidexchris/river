<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use UniversalStatus, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'branch_id',
        'staff_id',
        'method_code',
        'method_currency',
        'amount',
        'charge',
        'rate',
        'final_amount',
        'details',
        'btc_amount',
        'btc_wallet',
        'trx',
        'payment_try',
        'status',
        'from_api',
        'admin_feedback',
        'deposit_plan_id',
    ];

    protected $casts = [
        'details' => 'object',
    ];

    protected $hidden = ['details'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    /**
     * Get the staff that owns the deposit.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    /**
     * Get the branch that owns the deposit.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    // Scope
    public function scopeGatewayCurrency(): GatewayCurrency
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function scopeBaseCurrency()
    {
        $gateway = $this->gateway;

        return ($gateway && $gateway->crypto == ManageStatus::ACTIVE) ? 'USD' : $this->method_currency;
    }

    public function scopePending($query)
    {
        return $query->where('method_code', '>=', 1000)->where('status', ManageStatus::PAYMENT_PENDING);
    }

    public function scopeCancelled($query)
    {
        return $query->where('method_code', '>=', 1000)->where('status', ManageStatus::PAYMENT_CANCEL);
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
