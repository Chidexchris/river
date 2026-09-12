<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'act',
        'form_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public $casts = [
        'form_data' => 'object',
    ];

    public function jsonData(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'type'        => $this->type,
                'is_required' => $this->is_required,
                'label'       => $this->name,
                'extensions'  => $this->extensions ?? 'null',
                'options'     => json_encode($this->options),
                'old_id'      => '',
            ],
        );
    }

    public function mergeDefaultFields(array $fields): static
    {
        $formData = (array)$this->form_data;

        $defaultFields = array_map(function ($field) {
            return $this->prepareField($field);
        }, $fields);

        $formData        = array_merge($defaultFields, $formData);
        $this->form_data = $formData;

        return $this;
    }

    protected function prepareField(string $name): array
    {
        return [
            'name'        => $name,
            'label'       => titleToKey($name),
            'is_required' => 'required',
            'extensions'  => null,
            'options'     => [],
            'type'        => 'text',
        ];
    }

    /**
     * Get the other-bank associated with the form.
     */
    public function otherBank(): HasOne
    {
        return $this->hasOne(OtherBank::class, 'form_id', 'id');
    }

    /**
     * Get the wire-transfer-settings associated with the form.
     */
    public function wireTransferSettings(): HasOne
    {
        return $this->hasOne(WireTransferSettings::class, 'form_id', 'id');
    }

    /**
     * Get the loan plan associated with the form.
     */
    public function loanPlan(): HasOne
    {
        return $this->hasOne(LoanPlan::class, 'form_id', 'id');
    }
}
