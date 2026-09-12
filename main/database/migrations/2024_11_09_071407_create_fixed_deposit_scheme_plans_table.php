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
        Schema::create('fixed_deposit_scheme_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('icon', 255);
            $table->decimal('interest_rate', 5, 2);
            $table->unsignedInteger('interest_payout_interval');
            $table->unsignedInteger('lock_in_period');
            $table->decimal('minimum_amount', 28, 8);
            $table->decimal('maximum_amount', 28, 8);
            $table->boolean('status')->default(1)->comment('0 -> Plan is inactive, 1 -> Plan is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_deposit_scheme_plans');
    }
};
