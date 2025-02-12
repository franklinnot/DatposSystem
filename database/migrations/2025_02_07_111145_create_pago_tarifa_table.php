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
        Schema::create('pago_tarifa', function (Blueprint $table) {
            $table->id('id_pago_tarifa');
            $table->dateTime('fecha_pago');
            $table->date('fecha_inicio');
            $table->date('fecha_renovacion');
            $table->integer('duracion_meses');  
            $table->float('monto');
            $table->integer('estado');
            $table->unsignedBigInteger('id_tipo_tarifa', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_tarifa');
    }
};
