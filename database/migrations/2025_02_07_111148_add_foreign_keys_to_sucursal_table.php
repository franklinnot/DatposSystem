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
            $table->foreign(['id_empresa'], 'Relation_4')->references(['id_empresa'])->on('empresa')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursal', function (Blueprint $table) {
            $table->dropForeign('Relation_4');
        });
    }
};
