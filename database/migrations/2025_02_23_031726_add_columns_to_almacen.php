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
        Schema::table('almacen', function (Blueprint $table) {
            //
            $table->string('ciudad', 128)->nullable();
            $table->string('direccion', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('almacen', function (Blueprint $table) {
            //
            $table->dropColumn('ciudad');
            $table->dropColumn('direccion');
        });
    }
};
