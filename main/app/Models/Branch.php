<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use Searchable, UniversalStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'routing_number',
        'swift_code',
        'contact_number',
        'email',
        'address',
        'map_location',
        'status',
    ];

    /**
     * The staffs that belong to the branch.
     */
    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'branch_staff', 'branch_id', 'staff_id');
    }

    /**
     * Get the deposits for the branch.
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class, 'branch_id', 'id');
    }

    /**
     * Get the withdrawals for the branch.
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'branch_id', 'id');
    }

    /**
     * Get the transactions for the branch.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'branch_id', 'id');
    }

    /**
     * Get the users for the branch.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }
}
