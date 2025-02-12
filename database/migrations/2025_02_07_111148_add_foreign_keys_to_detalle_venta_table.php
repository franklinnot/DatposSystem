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
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->foreign(['id_detalle_lista_precios'], 'Relation_49')->references(['id_detalle_lista_precios'])->on('detalle_lista_precios')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_venta'], 'Relation_9')->references(['id_venta'])->on('venta')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropForeign('Relation_49');
            $table->dropForeign('Relation_9');
        });
    }
};
