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
            $table->unsignedBigInteger('verified_by')->nullable();

$table->foreign('verified_by')
      ->references('id')
      ->on('fuel_managers')
      ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_assignments', function (Blueprint $table) {
            //
        });
    }
};
