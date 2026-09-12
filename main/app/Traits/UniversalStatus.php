<?php

namespace App\Traits;

use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\RedirectResponse;

trait UniversalStatus
{
    public static function changeStatus(int $id, string $column = 'status'): RedirectResponse
    {
        $modelName = static::class;
        $query     = $modelName::findOrFail($id);

        if ($query->$column == ManageStatus::ACTIVE) {
            $query->$column = ManageStatus::INACTIVE;
        } else {
            $query->$column = ManageStatus::ACTIVE;
        }

        $query->save();

        $message = keyToTitle($column) . ' changed successfully';
        $toast[] = ['success', $message];

        return back()->with('toasts', $toast);
    }

    /**
     * Get the status badge.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::INACTIVE => '<span class="badge badge--warning">' . trans('Inactive') . '</span>',
                    default                => '<span class="badge badge--success">' . trans('Active') . '</span>',
                };
            },
        );
    }

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', ManageStatus::ACTIVE);
    }

    /**
     * Scope a query to only include inactive plans.
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('status', ManageStatus::INACTIVE);
    }
}
