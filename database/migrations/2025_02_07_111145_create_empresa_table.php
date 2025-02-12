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
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('ruc', 128);
            $table->string('razon_social', 128);
            $table->string('nombre_comercial', 128);
            $table->string('email', 128)->nullable();
            $table->string('telefono', 128)->nullable();
            $table->string('ciudad', 128)->nullable();
            $table->string('direccion', 128)->nullable();
            $table->float('igv');
            $table->float('monto_maximo_boleta');
            $table->string('numero_tributario', 128);
            $table->integer('cantidad_sucursales');
            $table->integer('cantidad_usuarios');
            $table->integer('facturacion_electronica');
            $table->binary('logo')->nullable();
            $table->date('fecha_renovacion')->nullable();
            $table->integer('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
