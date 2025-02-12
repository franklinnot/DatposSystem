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
        Schema::create('operacion', function (Blueprint $table) {
            $table->id('id_operacion');
            $table->string('codigo', 128);
            $table->dateTime('fecha_operacion');
            $table->dateTime('fecha_actualizacion')->nullable();
            $table->integer('estado');
            $table->unsignedBigInteger('id_almacen_origen', false);
            $table->unsignedBigInteger('id_almacen_destino', false)->nullable();
            $table->unsignedBigInteger('id_tipo_operacion', false);
            $table->unsignedBigInteger('id_usuario', false);
            $table->unsignedBigInteger('id_asociado', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacion');
    }
};
