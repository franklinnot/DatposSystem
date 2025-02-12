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
        Schema::create('sucursal_almacen', function (Blueprint $table) {
            $table->id('id_sucursal_almacen');
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_almacen', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal_almacen');
    }
};
