<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'mail_config'          => 'object',
        'sms_config'           => 'object',
        'universal_shortcodes' => 'object',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'email_template',
        'mail_config',
        'sms_config',
        'system_info',
    ];

    public function scopeSiteName($query, $pageTitle): string
    {
        $pageTitle = empty($pageTitle) ? '' : " | $pageTitle";

        return $this->site_name . $pageTitle;
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function () {
            cache()->forget('setting');
        });
    }
}
