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
        Schema::create('other_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->decimal('per_transaction_min_amount', 28, 8);
            $table->decimal('per_transaction_max_amount', 28, 8);
            $table->decimal('daily_transaction_max_amount', 28, 8);
            $table->unsignedInteger('daily_transaction_limit');
            $table->decimal('monthly_transaction_max_amount', 28, 8);
            $table->unsignedInteger('monthly_transaction_limit');
            $table->decimal('fixed_charge', 28, 8)->default(0);
            $table->decimal('percentage_charge', 5, 2)->default(0);
            $table->string('processing_time', 255);
            $table->text('instruction')->nullable();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->boolean('status')->default(true)->comment('0 -> bank is inactive, 1 -> bank is active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_banks');
    }
};
