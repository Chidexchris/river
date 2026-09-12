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
        Schema::create('fixed_deposit_schemes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan_name', 40);
            $table->string('scheme_code', 40);
            $table->decimal('interest_rate', 5);
            $table->decimal('deposit_amount', 28, 8)
                ->comment('The amount deposited at the time of opening the FDS');
            $table->unsignedInteger('interest_payout_interval');
            $table->decimal('per_installment', 28, 8)
                ->comment('The amount provided by the bank in each installment');
            $table->decimal('profit_amount', 28, 8)
                ->default(0)
                ->comment('The total profit amount');
            $table->date('next_installment_date');
            $table->date('locked_until');
            $table->timestamp('transfer_at')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment('0 -> closed, 1 -> running');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_deposit_schemes');
    }
};
