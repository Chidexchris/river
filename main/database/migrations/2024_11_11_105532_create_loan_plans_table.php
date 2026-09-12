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
        Schema::create('loan_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('icon', 255);
            $table->decimal('minimum_amount', 28, 8);
            $table->decimal('maximum_amount', 28, 8);
            $table->decimal('installment_rate', 5);
            $table->unsignedInteger('installment_interval');
            $table->unsignedInteger('total_installments');
            $table->text('instruction')->nullable();
            $table->unsignedInteger('delay_duration');
            $table->decimal('fixed_charge', 28, 8)->default(0);
            $table->decimal('percentage_charge', 5)->default(0);
            $table->unsignedBigInteger('form_id')->nullable();
            $table->boolean('status')->default(1)->comment('0 -> Plan is inactive, 1 -> Plan is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_plans');
    }
};
