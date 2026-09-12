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
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('beneficiary_id')->nullable()->change();
            $table->json('wire_transfer_payload')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('beneficiary_id')->nullable(false)->change();
            $table->dropColumn('wire_transfer_payload');
        });
    }
};
