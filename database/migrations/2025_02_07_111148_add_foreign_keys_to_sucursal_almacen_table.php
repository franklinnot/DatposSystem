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
        Schema::table('sucursal_almacen', function (Blueprint $table) {
            $table->foreign(['id_sucursal'], 'Relation_76')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_almacen'], 'Relation_77')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursal_almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_76');
            $table->dropForeign('Relation_77');
        });
    }
};
