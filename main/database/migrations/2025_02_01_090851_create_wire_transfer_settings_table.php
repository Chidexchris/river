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
        Schema::create('wire_transfer_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('per_transaction_min_amount', 28, 8);
            $table->decimal('per_transaction_max_amount', 28, 8);
            $table->decimal('daily_transaction_max_amount', 28, 8);
            $table->unsignedInteger('daily_transaction_limit');
            $table->decimal('monthly_transaction_max_amount', 28, 8);
            $table->unsignedInteger('monthly_transaction_limit');
            $table->decimal('fixed_charge', 28, 8)->default(0);
            $table->decimal('percentage_charge', 5)->default(0);
            $table->text('instruction')->nullable();
            $table->unsignedBigInteger('form_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wire_transfer_settings');
    }
};
