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
        Schema::table('producto_almacen', function (Blueprint $table) {
            //
            $table->dropColumn('costo_unitario');
        });
        Schema::table('producto', function (Blueprint $table) {
            //
            $table->float('costo_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_almacen', function (Blueprint $table) {
            //
            $table->float('costo_unitario');
        });
        Schema::table('producto', function (Blueprint $table) {
            //
            $table->dropColumn('costo_unitario');
        });
    }
};
