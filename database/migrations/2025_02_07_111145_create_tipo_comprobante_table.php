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
        Schema::create('tipo_comprobante', function (Blueprint $table) {
            $table->id('id_tipo_comprobante');
            $table->string('codigo', 128);
            $table->string('nombre', 128);
            $table->string('serie', 128);
            $table->integer('correlativo');
            $table->integer('ultimo_correlativo');
            $table->float('igv');
            $table->integer('estado');
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_comprobante');
    }
};
