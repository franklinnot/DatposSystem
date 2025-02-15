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
        Schema::table('precios_sucursal', function (Blueprint $table) {
            $table->unsignedBigInteger('id_empresa', false);
            $table->foreign(['id_empresa'], 'Relation_precios_sucursal_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precios_sucursal', function (Blueprint $table) {
            //
            $table->dropForeign('Relation_precios_sucursal_id_empresa');
            $table->dropColumn('id_empresa');
        });
    }
};
