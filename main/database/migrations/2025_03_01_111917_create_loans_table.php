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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('plan_name', 40);
            $table->string('scheme_code', 40);
            $table->decimal('amount_requested', 28, 8);
            $table->json('form_data')->nullable();
            $table->decimal('per_installment', 28, 8);
            $table->unsignedInteger('installment_interval');
            $table->unsignedInteger('total_installment');
            $table->unsignedInteger('given_installment')->default(0);
            $table->unsignedInteger('delay_duration');
            $table->decimal('per_installment_late_fee', 28, 8)->default(0);
            $table->decimal('total_late_fees', 28, 8)->default(0);
            $table->timestamp('late_fee_last_notified_at')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(3)
                ->comment('0 -> rejected, 1 -> running, 2 -> paid, 3 -> pending');
            $table->timestamp('approved_at')->nullable();
            $table->string('admin_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
