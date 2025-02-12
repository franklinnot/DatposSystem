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
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id('id_detalle_venta');
            $table->float('precio_unitario');
            $table->integer('cantidad');
            $table->float('subtotal_bruto');
            $table->float('descuento');
            $table->float('subtotal');
            $table->float('igv');
            $table->float('isc')->nullable();
            $table->float('total');
            $table->unsignedBigInteger('id_venta', false);
            $table->unsignedBigInteger('id_detalle_lista_precios', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};
