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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->enum('role', ['client', 'vendeur', 'admin'])->default('client');

            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();

            // Champs spécifiques vendeur
            $table->enum('vendor_status', [
                'pending',
                'approved',
                'rejected',
                'suspended'
            ])->nullable();

            $table->enum('identity_type', ['cin', 'passport'])->nullable();
            $table->string('identity_document')->nullable();

            $table->timestamps();

            // Soft Delete
            $table->softDeletes();

            // index
            $table->index('role');
            $table->index('vendor_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
