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
        Schema::table('turno_caja', function (Blueprint $table) {
            $table->foreign(['id_caja'], 'Relation_60')->references(['id_caja'])->on('caja')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_usuario'], 'Relation_66')->references(['id_usuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turno_caja', function (Blueprint $table) {
            $table->dropForeign('Relation_60');
            $table->dropForeign('Relation_66');
        });
    }
};
