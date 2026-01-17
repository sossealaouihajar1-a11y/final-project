<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vintage_products', function (Blueprint $table) {
            $table->enum('category', [
                'mode',
                'mobilier',
                'accessoires',
                'electronique_vintage',
                'art',
                'autre'
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('vintage_products', function (Blueprint $table) {
            // Revenir à string si rollback
            $table->string('category')->change();
        });
    }
};
