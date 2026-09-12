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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('image', 255)->nullable();
            $table->string('username', 40)->unique();
            $table->string('email_address', 40)->unique();
            $table->string('contact_number', 40)->unique();
            $table->string('address', 255);
            $table->unsignedTinyInteger('designation');
            $table->string('password', 255);
            $table->boolean('status')->default(true)->comment('0: Inactive, 1: Active');
            $table->string('remember_token', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
