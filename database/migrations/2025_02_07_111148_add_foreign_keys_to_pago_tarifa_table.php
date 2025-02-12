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
        Schema::table('pago_tarifa', function (Blueprint $table) {
            $table->foreign(['id_tipo_tarifa'], 'Relation_71')->references(['id_tipo_tarifa'])->on('tipo_tarifa')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_empresa'], 'Relation_72')->references(['id_empresa'])->on('empresa')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pago_tarifa', function (Blueprint $table) {
            $table->dropForeign('Relation_71');
            $table->dropForeign('Relation_72');
        });
    }
};
