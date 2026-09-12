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
        Schema::create('deposit_pension_scheme_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('icon', 255);
            $table->decimal('per_installment', 28, 8);
            $table->unsignedInteger('installment_interval');
            $table->unsignedInteger('total_installment');
            $table->decimal('total_deposit_amount', 28, 8);
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('profit_amount', 28, 8);
            $table->decimal('maturity_amount', 28, 8);
            $table->unsignedInteger('delay_duration');
            $table->decimal('fixed_charge', 28, 8);
            $table->decimal('percentage_charge', 5, 2);
            $table->boolean('status')->default(1)->comment('0 -> Plan is inactive, 1 -> Plan is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_pension_scheme_plans');
    }
};
