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
            $table->dropForeign('Relation_76');
            $table->dropForeign('Relation_77');
            $table->dropForeign('Relation_sucursal_almacen_id_empresa');

        });
        Schema::dropIfExists('sucursal_almacen');

        Schema::table('anulacion', function (Blueprint $table) {
            $table->dropForeign('Relation_80');
            $table->dropForeign('Relation_anulacion_id_empresa');
        });
        
        Schema::dropIfExists('anulacion');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sucursal_almacen', function (Blueprint $table) {
            $table->id('id_sucursal_almacen');
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_almacen', false);
            $table->unsignedBigInteger('id_empresa', false);
            
            $table->foreign(['id_sucursal'], 'Relation_76')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_almacen'], 'Relation_77')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_empresa'], 'Relation_sucursal_almacen_id_empresa')
            ->references(['id_empresa'])
            ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        //
        Schema::create('anulacion', function (Blueprint $table) {
            $table->id('id_anulacion');
            $table->text('motivo')->nullable();
            $table->dateTime('fecha_anulacion');
            $table->unsignedBigInteger('id_comprobante_pago', false);
            $table->unsignedBigInteger('id_empresa', false);

            $table->foreign(['id_comprobante_pago'], 'Relation_80')->references(['id_comprobante_pago'])->on('comprobante_pago')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_empresa'], 'Relation_anulacion_id_empresa')
            ->references(['id_empresa'])
            ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }
};
