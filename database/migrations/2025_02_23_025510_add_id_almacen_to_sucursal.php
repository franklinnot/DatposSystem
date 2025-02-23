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
        Schema::table('sucursal', function (Blueprint $table) {
            // nueva columna y fk
            $table->unsignedBigInteger('id_almacen', false)->nullable();

            $table->foreign(['id_almacen'], 'FK_id_almacen_to_sucursal')
            ->references(['id_almacen'])
            ->on('almacen')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursal', function (Blueprint $table) {
            $table->dropColumn('id_almacen');
            $table->dropForeign('FK_id_almacen_to_sucursal');
        });
    }
};
