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
        Schema::table('operacion', function (Blueprint $table) {
            //
            // eliminar codigo, agregar serie y numero
            $table->dropColumn('codigo');
            $table->string('serie', 128);
            $table->string('numero', 128);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operacion', function (Blueprint $table) {
            //
            $table->string('codigo');
            $table->dropColumn('serie');
            $table->dropColumn('numero');
        });
    }
};
