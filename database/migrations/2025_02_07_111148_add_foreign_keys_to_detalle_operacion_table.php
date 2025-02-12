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
        Schema::table('detalle_operacion', function (Blueprint $table) {
            $table->foreign(['id_operacion'], 'Relation_58')->references(['id_operacion'])->on('operacion')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_producto'], 'Relation_59')->references(['id_producto'])->on('producto')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_operacion', function (Blueprint $table) {
            $table->dropForeign('Relation_58');
            $table->dropForeign('Relation_59');
        });
    }
};
