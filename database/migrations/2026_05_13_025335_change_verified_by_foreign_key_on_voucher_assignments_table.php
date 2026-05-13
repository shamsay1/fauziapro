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
        Schema::table('voucher_assignments', function (Blueprint $table) {

            $table->dropForeign([
                'verified_by'
            ]);
            $table->foreign('verified_by')
                  ->references('id')
                  ->on('system_users')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_assignments', function (Blueprint $table) {

            // REMOVE NEW FOREIGN KEY
            $table->dropForeign([
                'verified_by'
            ]);

            // RESTORE OLD FOREIGN KEY
            $table->foreign('verified_by')
                  ->references('id')
                  ->on('fuel_workers')
                  ->onDelete('cascade');

        });
    }
};
