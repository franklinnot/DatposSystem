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
        Schema::create('retiro_dinero', function (Blueprint $table) {
            $table->id('id_retiro_dinero');
            $table->dateTime('fecha_retiro');
            $table->float('saldo_actual');
            $table->float('monto_retirado');
            $table->float('saldo_restante');
            $table->unsignedBigInteger('id_turno_caja', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retiro_dinero');
    }
};
