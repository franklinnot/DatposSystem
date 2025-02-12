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
        Schema::table('lista_precios', function (Blueprint $table) {
            $table->foreign(['id_sucursal'], 'Relation_38')->references(['id_sucursal'])->on('sucursal')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lista_precios', function (Blueprint $table) {
            $table->dropForeign('Relation_38');
        });
    }
};
