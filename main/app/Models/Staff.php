<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use Searchable, UniversalStatus;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'staffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'username',
        'email_address',
        'contact_number',
        'address',
        'designation',
        'password',
        'status',
        'remember_token',
    ];

    /**
     * The branches that belong to the staff.
     */
    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_staff', 'staff_id', 'branch_id');
    }

    /**
     * Get the deposits for the staff.
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class, 'staff_id', 'id');
    }

    /**
     * Get the withdrawals for the staff.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'staff_id', 'id');
    }

    /**
     * Get the transactions for the staff.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'staff_id', 'id');
    }

    /**
     * Get the users for the staff.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'staff_id', 'id');
    }
}
