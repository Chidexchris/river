<?php

use App\Constants\ManageStatus;
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
        Schema::create('money_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->string('trx', 40);
            $table->decimal('amount', 28, 8);
            $table->decimal('charge', 28, 8)->default(0);
            $table->unsignedTinyInteger('status')->default(ManageStatus::MONEY_TRANSFER_PENDING)
                ->comment('0 -> failed, 1 -> completed, 2 -> pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_transfers');
    }
};
