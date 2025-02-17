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
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            //
            $table->string('serie', 128);
            $table->dropIndex('IX_tipo_comprobante_id_empresa');
            $table->dropForeign('Relation_tipo_comprobante_id_empresa');
            $table->dropColumn('id_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            //
            $table->dropColumn('serie');
            $table->unsignedBigInteger('id_empresa', false);
            $table->index('id_empresa', 'IX_tipo_comprobante_id_empresa');
            $table->foreign(['id_empresa'], 'Relation_tipo_comprobante_id_empresa')
                ->references(['id_empresa'])
                ->on('empresa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }
};
