<?php

namespace App\Models;

use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Gateway extends Model
{
    use UniversalStatus;

    protected $hidden = [
        'gateway_parameters', 'extra'
    ];

    protected $casts = [
        'code'                 => 'string',
        'extra'                => 'object',
        'input_form'           => 'object',
        'supported_currencies' => 'object',
    ];

    public function currencies(): HasMany
    {
        return $this->hasMany(GatewayCurrency::class, 'method_code', 'code');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function singleCurrency(): HasOne
    {
        return $this->hasOne(GatewayCurrency::class, 'method_code', 'code')->orderByDesc('id');
    }

    public function scopeAutomated($query)
    {
        return $query->where('code', '<', 1000);
    }

    public function scopeManual($query)
    {
        return $query->where('code', '>=', 1000);
    }
}
