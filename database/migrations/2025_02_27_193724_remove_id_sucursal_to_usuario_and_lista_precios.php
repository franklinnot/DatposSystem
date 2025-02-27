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
            //
            $table->dropForeign('Relation_64');
            $table->dropColumn('id_sucursal');
        });
        Schema::table('lista_precios', function (Blueprint $table) {
            //
            $table->dropForeign('Relation_38');
            $table->dropColumn('id_sucursal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_sucursal', false);
            $table->foreign(['id_sucursal'], 'Relation_64')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
        });
        Schema::table('lista_precios', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_sucursal', false);
            $table->foreign(['id_sucursal'], 'Relation_38')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
        });
    }
};
