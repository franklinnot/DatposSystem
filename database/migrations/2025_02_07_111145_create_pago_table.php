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
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->float('monto_pagado');
            $table->float('cambio');
            $table->string('numero_tarjeta', 128)->nullable();
            $table->integer('estado');
            $table->unsignedBigInteger('id_metodo_pago', false);
            $table->unsignedBigInteger('id_venta', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
