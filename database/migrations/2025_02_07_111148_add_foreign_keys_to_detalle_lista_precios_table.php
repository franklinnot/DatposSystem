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
        Schema::table('detalle_lista_precios', function (Blueprint $table) {
            $table->foreign(['id_lista_precios'], 'Relation_65')->references(['id_lista_precios'])->on('lista_precios')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_producto'], 'Relation_68')->references(['id_producto'])->on('producto')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_lista_precios', function (Blueprint $table) {
            $table->dropForeign('Relation_65');
            $table->dropForeign('Relation_68');
        });
    }
};
