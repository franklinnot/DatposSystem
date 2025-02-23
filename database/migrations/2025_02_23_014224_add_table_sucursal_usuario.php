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
        Schema::create('usuario_sucursal', function (Blueprint $table) {
            //
            $table->id('id_usuario_sucursal');
            $table->unsignedBigInteger('id_usuario', false);
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_empresa', false);
            
            $table->foreign(['id_usuario'], 'FK_usuario_sucursal_empresa')
                ->references(['id_usuario'])
                ->on('usuario')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign(['id_sucursal'], 'FK_usuario_sucursal_sucursal')
                ->references(['id_sucursal'])
                ->on('sucursal')
                ->onUpdate('no action')
                ->onDelete('no action');
            
            $table->foreign(['id_empresa'], 'Relation_usuario_sucursal_id_empresa')
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
        Schema::table('usuario_sucursal', function (Blueprint $table) {
            $table->dropForeign('FK_usuario_sucursal_empresa');
            $table->dropForeign('FK_usuario_sucursal_sucursal');
            $table->dropForeign('Relation_usuario_sucursal_id_empresa');
        });

        Schema::dropIfExists('usuario_almacen');
    }
};
