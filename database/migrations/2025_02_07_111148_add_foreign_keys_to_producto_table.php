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
        Schema::table('producto', function (Blueprint $table) {
            $table->foreign(['id_unidad_medida'], 'Relation_31')->references(['id_unidad_medida'])->on('unidad_medida')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_familia'], 'Relation_48')->references(['id_familia'])->on('familia')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_tipo_producto'], 'Relation_85')->references(['id_tipo_producto'])->on('tipo_producto')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->dropForeign('Relation_31');
            $table->dropForeign('Relation_48');
            $table->dropForeign('Relation_85');
        });
    }
};
