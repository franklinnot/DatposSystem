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
        Schema::create('detalle_operacion', function (Blueprint $table) {
            $table->id('id_detalle_operacion');
            $table->float('costo_unitario');
            $table->integer('cantidad');
            $table->unsignedBigInteger('id_operacion', false);
            $table->unsignedBigInteger('id_producto', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_operacion');
    }
};
