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
        Schema::table('retiro_dinero', function (Blueprint $table) {
            $table->foreign(['id_turno_caja'], 'Relation_74')->references(['id_turno_caja'])->on('turno_caja')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retiro_dinero', function (Blueprint $table) {
            $table->dropForeign('Relation_74');
        });
    }
};
