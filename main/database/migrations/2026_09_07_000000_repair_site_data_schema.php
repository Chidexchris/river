<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the site-content fields omitted from the original table migration.
     */
    public function up(): void
    {
        Schema::table('site_data', function (Blueprint $table) {
            if (!Schema::hasColumn('site_data', 'data_key')) {
                $table->string('data_key', 40)->nullable()->after('id');
            }

            if (!Schema::hasColumn('site_data', 'data_info')) {
                $table->longText('data_info')->nullable()->after('data_key');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_data', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('site_data', 'data_info')) {
                $columns[] = 'data_info';
            }

            if (Schema::hasColumn('site_data', 'data_key')) {
                $columns[] = 'data_key';
            }

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};
