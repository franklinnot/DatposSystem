<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asociado', function (Blueprint $table) {
            $table->id('id_asociado');
            $table->string('ruc', 32)->nullable();
            $table->string('dni', 16)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->string('telefono', 32)->nullable();
            $table->string('correo', 255)->nullable();
            $table->integer('tipo_asociado');
            $table->integer('estado');
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociado');
    }
};
