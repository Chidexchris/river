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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('code', 40)->unique();
            $table->unsignedInteger('routing_number')->unique();
            $table->string('swift_code', 40)->unique();
            $table->string('contact_number', 40)->nullable();
            $table->string('email', 40)->nullable()->unique();
            $table->text('address');
            $table->text('map_location')->nullable();
            $table->boolean('status')->default(1)->comment('0: Inactive, 1: Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
