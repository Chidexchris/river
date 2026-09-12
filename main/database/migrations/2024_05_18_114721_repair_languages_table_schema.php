<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the columns consumed by the language middleware and admin screens.
     */
    public function up(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            // Column order is cosmetic and `after()` is MySQL-specific.
            $table->string('name', 40)->nullable();
            $table->string('code', 40)->nullable()->unique();
            $table->boolean('is_default')->default(false);
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the schema repair.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['name', 'code', 'is_default', 'status']);
        });
    }
};
