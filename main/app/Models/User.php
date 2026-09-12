<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable, UniversalStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'staff_id',
        'account_number',
        'image',
        'firstname',
        'lastname',
        'username',
        'email',
        'country_code',
        'country_name',
        'mobile',
        'ref_by',
        'referral_action_limit',
        'balance',
        'password',
        'address',
        'status',
        'kyc_data',
        'kc',
        'ec',
        'sc',
        'ver_code',
        'ver_code_send_at',
        'ts',
        'tc',
        'tsc',
        'ban_reason',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code', 'balance', 'kyc_data', 'kyf_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
    ];

    /**
     * Get the user's full name.
     */
    public function fullname(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    /**
     * Get the email status badge.
     */
    protected function emailStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->ec) {
                    ManageStatus::VERIFIED => '<span class="badge badge--success">' . trans('Verified') . '</span>',
                    default                => '<span class="badge badge--warning">' . trans('Unverified') . '</span>',
                };
            },
        );
    }

    /**
     * Get the mobile status badge.
     */
    protected function mobileStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->sc) {
                    ManageStatus::VERIFIED => '<span class="badge badge--success">' . trans('Verified') . '</span>',
                    default                => '<span class="badge badge--warning">' . trans('Unverified') . '</span>',
                };
            },
        );
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ref_by', 'id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'ref_by', 'id');
    }

    /**
     * Get the branch that owns the user.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /**
     * Get the staff that owns the user.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    /**
     * Get the money-transfers for the user.
     */
    public function moneyTransfers(): HasMany
    {
        return $this->hasMany(MoneyTransfer::class, 'user_id', 'id');
    }

    /**
     * Get all the user's beneficiaries.
     */
    public function beneficiaries(): MorphMany
    {
        return $this->morphMany(Beneficiary::class, 'beneficiaryable');
    }

    /**
     * Get the deposit-pension-schemes for the user.
     */
    public function depositPensionSchemes(): HasMany
    {
        return $this->hasMany(DepositPensionScheme::class, 'user_id', 'id');
    }

    /**
     * Get the fixed-deposit-schemes for the user.
     */
    public function fixedDepositSchemes(): HasMany
    {
        return $this->hasMany(FixedDepositScheme::class, 'user_id', 'id');
    }

    /**
     * Get the loans for the user.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'user_id', 'id');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', ManageStatus::ACTIVE)->where('ec', ManageStatus::VERIFIED)->where('sc', ManageStatus::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', ManageStatus::INACTIVE);
    }

    public function scopeEmailUnconfirmed($query)
    {
        return $query->where('ec', ManageStatus::UNVERIFIED);
    }

    public function scopeMobileUnconfirmed($query)
    {
        return $query->where('sc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycUnconfirmed($query)
    {
        return $query->where('kc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kc', ManageStatus::PENDING);
    }
}
