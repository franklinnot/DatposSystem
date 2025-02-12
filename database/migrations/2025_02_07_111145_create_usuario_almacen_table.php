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
        Schema::create('usuario_almacen', function (Blueprint $table) {
            $table->id('id_usuario_almacen');
            $table->unsignedBigInteger('id_usuario', false);
            $table->unsignedBigInteger('id_almacen', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_almacen');
    }
};
