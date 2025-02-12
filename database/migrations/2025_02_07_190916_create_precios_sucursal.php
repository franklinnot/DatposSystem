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
        Schema::create('precios_sucursal', function (Blueprint $table) {
            $table->id('id_precios_sucursal');
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_lista_precios', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precios_sucursal');
    }
};
