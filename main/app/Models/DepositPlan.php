<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_name',
        'daily_amount',
        'currency',
        'duration_days',
        'start_date',
        'status',
    ];

    // Optional: relation to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
