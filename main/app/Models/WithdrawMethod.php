<?php

namespace App\Models;

use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawMethod extends Model
{
    use UniversalStatus;

    protected $casts = [
        'user_data' => 'object',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
