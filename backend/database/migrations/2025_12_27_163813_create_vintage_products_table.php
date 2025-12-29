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
        Schema::create('vintage_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vendeur_id');
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->decimal('promotion', 5, 2)->default(0);
            $table->enum('condition', ['neuf', 'excellent', 'tres_bon', 'bon', 'acceptable', 'a_restaurer']);
            $table->integer('stock')->default(0);
            $table->enum('status', ['active', 'inactive', 'sold_out', 'pending'])->default('pending');
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key
            $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index
            $table->index('vendeur_id');
            $table->index('category');
            $table->index('status');
            $table->index(['status', 'created_at']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vintage_products');
    }
};