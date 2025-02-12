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
        Schema::create('comprobante_pago', function (Blueprint $table) {
            $table->id('id_comprobante_pago');
            $table->string('codigo', 128);
            $table->integer('estado');
            $table->unsignedBigInteger('id_tipo_comprobante', false);
            $table->unsignedBigInteger('id_venta', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_pago');
    }
};
