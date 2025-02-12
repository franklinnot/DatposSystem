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
        Schema::create('lista_precios', function (Blueprint $table) {
            $table->id('id_lista_precios');
            $table->string('codigo', 128);
            $table->string('descripcion', 128)->nullable();
            $table->date('fecha_inicio_vigencia');
            $table->date('fecha_fin_vigencia')->nullable();
            $table->boolean('es_preferencial');
            $table->integer('estado');
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_precios');
    }
};
