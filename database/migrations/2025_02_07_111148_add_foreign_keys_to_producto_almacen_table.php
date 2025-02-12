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
        Schema::table('producto_almacen', function (Blueprint $table) {
            $table->foreign(['id_almacen'], 'Relation_57')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_producto'], 'Relation_89')->references(['id_producto'])->on('producto')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto_almacen', function (Blueprint $table) {
            $table->dropForeign('Relation_57');
            $table->dropForeign('Relation_89');
        });
    }
};
