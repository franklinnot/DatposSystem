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
        Schema::create('producto_almacen', function (Blueprint $table) {
            $table->id('id_producto_almacen');
            $table->integer('stock');
            $table->float('costo_unitario');
            $table->unsignedBigInteger('id_producto', false);
            $table->unsignedBigInteger('id_almacen', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_almacen');
    }
};
