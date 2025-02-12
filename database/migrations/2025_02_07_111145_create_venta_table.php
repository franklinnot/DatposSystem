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
        Schema::create('venta', function (Blueprint $table) {
            $table->id('id_venta');
            $table->float('suma_subtotal_bruto');
            $table->float('suma_descuento')->nullable();
            $table->float('suma_subtotal');
            $table->float('suma_impuesto');
            $table->float('suma_total');
            $table->boolean('es_preventa');
            $table->dateTime('fecha_venta')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->integer('estado');
            $table->unsignedBigInteger('id_turno_caja', false);
            $table->unsignedBigInteger('id_asociado', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};
