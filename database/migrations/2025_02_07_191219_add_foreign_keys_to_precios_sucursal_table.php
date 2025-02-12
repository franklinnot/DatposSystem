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
            $table->foreign(['id_sucursal'], 'Relation_123')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_lista_precios'], 'Relation_130')->references(['id_lista_precios'])->on('lista_precios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('precios_sucursal', function (Blueprint $table) {
            $table->dropForeign('Relation_123');
            $table->dropForeign('Relation_130');
        });
    }
};
