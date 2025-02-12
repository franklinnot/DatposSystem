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
        Schema::table('operacion', function (Blueprint $table) {
            $table->foreign(['id_almacen_origen'], 'Relation_39')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_almacen_destino'], 'Relation_52')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_usuario'], 'Relation_67')->references(['id_usuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tipo_operacion'], 'Relation_69')->references(['id_tipo_operacion'])->on('tipo_operacion')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_asociado'], 'Relation_79')->references(['id_asociado'])->on('asociado')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operacion', function (Blueprint $table) {
            $table->dropForeign('Relation_39');
            $table->dropForeign('Relation_52');
            $table->dropForeign('Relation_67');
            $table->dropForeign('Relation_69');
            $table->dropForeign('Relation_79');
        });
    }
};
