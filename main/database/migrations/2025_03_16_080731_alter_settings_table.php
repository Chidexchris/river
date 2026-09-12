<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->unsignedSmallInteger('idle_timeout')->comment('value in seconds');
            $table->decimal('statement_download_fee', 28, 8)->default(0);
            $table->boolean('auto_logout')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'idle_timeout',
                'statement_download_fee',
                'auto_logout',
            ]);
        });
    }
};
