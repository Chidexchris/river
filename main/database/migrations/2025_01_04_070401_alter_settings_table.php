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
            $table->boolean('deposit')->default(true);
            $table->boolean('withdraw')->default(true);
            $table->boolean('dps')->default(true);
            $table->boolean('fds')->default(true);
            $table->boolean('loan')->default(true);
            $table->boolean('internal_bank_transfer')->default(true);
            $table->boolean('external_bank_transfer')->default(true);
            $table->boolean('wire_transfer')->default(true);
            $table->boolean('sms_based_otp')->default(true);
            $table->boolean('email_based_otp')->default(true);
            $table->boolean('push_notification')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'deposit',
                'withdraw',
                'dps',
                'fds',
                'loan',
                'internal_bank_transfer',
                'external_bank_transfer',
                'wire_transfer',
                'sms_based_otp',
                'email_based_otp',
                'push_notification',
            ]);
        });
    }
};
