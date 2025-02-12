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
        Schema::create('tipo_tarifa', function (Blueprint $table) {
            $table->id('id_tipo_tarifa');
            $table->string('nombre');
            $table->float('monto');
            $table->integer('duracion_meses');
            $table->integer('cantidad_sucursales');
            $table->integer('cantidad_usuarios');
            $table->integer('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_tarifa');
    }
};
