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
        Schema::create('turno_caja', function (Blueprint $table) {
            $table->id('id_turno_caja');
            $table->string('codigo', 128);
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->float('saldo_inicial');
            $table->float('total_ventas')->nullable();
            $table->float('total_retirado')->nullable();
            $table->float('saldo_facturado')->nullable();
            $table->float('saldo_entregado')->nullable();
            $table->float('diferencia_saldo')->nullable();
            $table->integer('estado');
            $table->unsignedBigInteger('id_usuario', false);
            $table->unsignedBigInteger('id_caja', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turno_caja');
    }
};
