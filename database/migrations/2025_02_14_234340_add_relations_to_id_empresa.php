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
        Schema::table('acceso_rol', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_acceso_rol_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('almacen', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_almacen_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('anulacion', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_anulacion_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('asociado', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_asociado_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('caja', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_caja_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('comprobante_pago', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_comprobante_pago_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('detalle_lista_precios', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_detalle_lista_precios_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('detalle_operacion', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_detalle_operacion_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('detalle_variante', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_detalle_variante_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_detalle_venta_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('familia', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_familia_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('lista_precios', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_lista_precios_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('operacion', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_operacion_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('pago', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_pago_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('producto', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_producto_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('producto_almacen', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_producto_almacen_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('retiro_dinero', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_retiro_dinero_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('rol', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_rol_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('sucursal_almacen', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_sucursal_almacen_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_tipo_comprobante_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('tipo_operacion', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_tipo_operacion_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('turno_caja', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_turno_caja_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('unidad_medida', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_unidad_medida_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('usuario_almacen', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_usuario_almacen_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('variante', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_variante_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
        Schema::table('venta', function (Blueprint $table) {
            $table->foreign(['id_empresa'], 'Relation_venta_id_empresa')
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
        Schema::table('acceso_rol', function (Blueprint $table) {
            $table->dropForeign('Relation_acceso_rol_id_empresa');
        });
        Schema::table('almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_almacen_id_empresa');
        });
        Schema::table('anulacion', function (Blueprint $table) {
            $table->dropForeign('Relation_anulacion_id_empresa');
        });
        Schema::table('asociado', function (Blueprint $table) {
            $table->dropForeign('Relation_asociado_id_empresa');
        });
        Schema::table('caja', function (Blueprint $table) {
            $table->dropForeign('Relation_caja_id_empresa');
        });
        Schema::table('comprobante_pago', function (Blueprint $table) {
            $table->dropForeign('Relation_comprobante_pago_id_empresa');
        });
        Schema::table('detalle_lista_precios', function (Blueprint $table) {
            $table->dropForeign('Relation_detalle_lista_precios_id_empresa');
        });
        Schema::table('detalle_operacion', function (Blueprint $table) {
            $table->dropForeign('Relation_detalle_operacion_id_empresa');
        });
        Schema::table('detalle_variante', function (Blueprint $table) {
            $table->dropForeign('Relation_detalle_variante_id_empresa');
        });
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropForeign('Relation_detalle_venta_id_empresa');
        });
        Schema::table('familia', function (Blueprint $table) {
            $table->dropForeign('Relation_familia_id_empresa');
        });
        Schema::table('lista_precios', function (Blueprint $table) {
            $table->dropForeign('Relation_lista_precios_id_empresa');
        });
        Schema::table('operacion', function (Blueprint $table) {
            $table->dropForeign('Relation_operacion_id_empresa');
        });
        Schema::table('pago', function (Blueprint $table) {
            $table->dropForeign('Relation_pago_id_empresa');
        });
        Schema::table('producto', function (Blueprint $table) {
            $table->dropForeign('Relation_producto_id_empresa');
        });
        Schema::table('producto_almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_producto_almacen_id_empresa');
        });
        Schema::table('retiro_dinero', function (Blueprint $table) {
            $table->dropForeign('Relation_retiro_dinero_id_empresa');
        });
        Schema::table('rol', function (Blueprint $table) {
            $table->dropForeign('Relation_rol_id_empresa');
        });
        Schema::table('sucursal_almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_sucursal_almacen_id_empresa');
        });
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            $table->dropForeign('Relation_tipo_comprobante_id_empresa');
        });
        Schema::table('tipo_operacion', function (Blueprint $table) {
            $table->dropForeign('Relation_tipo_operacion_id_empresa');
        });
        Schema::table('turno_caja', function (Blueprint $table) {
            $table->dropForeign('Relation_turno_caja_id_empresa');
        });
        Schema::table('unidad_medida', function (Blueprint $table) {
            $table->dropForeign('Relation_unidad_medida_id_empresa');
        });
        Schema::table('usuario_almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_usuario_almacen_id_empresa');
        });
        Schema::table('variante', function (Blueprint $table) {
            $table->dropForeign('Relation_variante_id_empresa');
        });
        Schema::table('venta', function (Blueprint $table) {
            $table->dropForeign('Relation_venta_id_empresa');
        });
    }
};
