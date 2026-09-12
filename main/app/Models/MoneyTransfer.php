<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoneyTransfer extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'beneficiary_id',
        'trx',
        'amount',
        'charge',
        'wire_transfer_payload',
        'status',
        'rejection_reason',
    ];

    /**
     * Get the user that owns the money-transfer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the beneficiary that owns the money-transfer.
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }

    /**
     * Scope a query to only include pending transfers.
     */
    public function scopePending(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::MONEY_TRANSFER_PENDING);
    }

    /**
     * Scope a query to only include completed transfers.
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::MONEY_TRANSFER_COMPLETED);
    }

    /**
     * Scope a query to only include failed transfers.
     */
    public function scopeFailed(Builder $query): void
    {
        $query->where('status', '=', ManageStatus::MONEY_TRANSFER_FAILED);
    }

    /**
     * Converts the stored 'wire_transfer_payload' value into an array if it is not already.
     * Iterates through the payload array and encrypts the 'value' of any item
     * that has a 'type' of 'file'.
     */
    protected function wireTransferPayload(): Attribute
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
     * Get the status badge.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::MONEY_TRANSFER_COMPLETED => '<span class="badge badge--success">' . trans('Completed') . '</span>',
                    ManageStatus::MONEY_TRANSFER_FAILED    => '<span class="badge badge--danger">' . trans('Failed') . '</span>',
                    default                                => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                };
            },
        );
    }
}
