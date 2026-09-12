<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $legacyNames = ['Tona' . 'Bank', 'Tona ' . 'Bank', 'Tona ' . 'Admin'];

        Setting::query()
            ->whereIn('site_name', $legacyNames)
            ->update(['site_name' => 'RiverWind Bank']);
    }

    public function down(): void
    {
        Setting::query()
            ->where('site_name', 'RiverWind Bank')
            ->update(['site_name' => 'Tona' . 'Bank']);
    }
};