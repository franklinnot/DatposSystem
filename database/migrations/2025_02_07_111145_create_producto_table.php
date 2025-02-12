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
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('codigo', 128);
            $table->string('codigo_producto_sunat', 128);
            $table->string('nombre', 128);
            $table->integer('stock_minimo')->nullable();
            $table->integer('stock_maximo')->nullable();
            $table->float('isc')->nullable();
            $table->binary('imagen')->nullable();
            $table->boolean('recibir_alerta');
            $table->boolean('estado');
            $table->unsignedBigInteger('id_familia', false);
            $table->unsignedBigInteger('id_tipo_producto', false);
            $table->unsignedBigInteger('id_unidad_medida', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
