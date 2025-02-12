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
        Schema::table('comprobante_pago', function (Blueprint $table) {
            $table->foreign(['id_venta'], 'Relation_50')->references(['id_venta'])->on('venta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tipo_comprobante'], 'Relation_55')->references(['id_tipo_comprobante'])->on('tipo_comprobante')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comprobante_pago', function (Blueprint $table) {
            $table->dropForeign('Relation_50');
            $table->dropForeign('Relation_55');
        });
    }
};
