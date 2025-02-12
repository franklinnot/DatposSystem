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
        Schema::table('usuario_almacen', function (Blueprint $table) {
            $table->foreign(['id_almacen'], 'FK_usuario_almacen_almacen')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_usuario'], 'FK_usuario_almacen_usuario')->references(['id_usuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario_almacen', function (Blueprint $table) {
            $table->dropForeign('FK_usuario_almacen_almacen');
            $table->dropForeign('FK_usuario_almacen_usuario');
        });
    }
};
