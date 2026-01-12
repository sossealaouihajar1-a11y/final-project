<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'payment_method')) {
            $table->enum('payment_method', ['stripe', 'cash_on_delivery'])->nullable()->after('status');
        }
    });

    Schema::table('invoices', function (Blueprint $table) {
        if (!Schema::hasColumn('invoices', 'payment_method')) {
            $table->enum('payment_method', ['stripe', 'cash_on_delivery'])->nullable()->after('status');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
