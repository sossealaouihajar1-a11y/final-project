<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pour MySQL/MariaDB
        DB::statement("ALTER TABLE vintage_products MODIFY category ENUM('art', 'accessoires', 'mode', 'mobilier', 'electronique_vintage','autre') NOT NULL");
        
       
    }

    public function down(): void
    {
        Schema::table('vintage_products', function (Blueprint $table) {
            $table->string('category')->change();
        });
    }
};