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
        Schema::table('acceso_rol', function (Blueprint $table) {
            $table->foreign(['id_rol'], 'acceso_rol_id')->references(['id_rol'])->on('rol')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_acceso'], 'Relation_61')->references(['id_acceso'])->on('acceso')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acceso_rol', function (Blueprint $table) {
            $table->dropForeign('acceso_rol_id');
            $table->dropForeign('Relation_61');
        });
    }
};
