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
        Schema::create('detalle_lista_precios', function (Blueprint $table) {
            $table->id('id_detalle_lista_precios');
            $table->float('precio_unitario');
            $table->float('descuento_maximo')->nullable();
            $table->unsignedBigInteger('id_lista_precios', false);
            $table->unsignedBigInteger('id_producto', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_lista_precios');
    }
};
