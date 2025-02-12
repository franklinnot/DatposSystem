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
        Schema::table('pago', function (Blueprint $table) {
            $table->foreign(['id_venta'], 'Relation_70')->references(['id_venta'])->on('venta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_metodo_pago'], 'Relation_73')->references(['id_metodo_pago'])->on('metodo_pago')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->dropForeign('Relation_70');
            $table->dropForeign('Relation_73');
        });
    }
};
