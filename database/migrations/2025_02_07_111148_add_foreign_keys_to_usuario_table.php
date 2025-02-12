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
        Schema::table('usuario', function (Blueprint $table) {
            $table->foreign(['id_almacen'], 'Relation_47')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_sucursal'], 'Relation_64')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_empresa'], 'Relation_75')->references(['id_empresa'])->on('empresa')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_rol'], 'Relation_87')->references(['id_rol'])->on('rol')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropForeign('Relation_47');
            $table->dropForeign('Relation_64');
            $table->dropForeign('Relation_75');
            $table->dropForeign('Relation_87');
        });
    }
};
