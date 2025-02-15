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
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            //
            $table->dropColumn('serie');
            $table->dropColumn('correlativo');
            $table->dropColumn('ultimo_correlativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_comprobante', function (Blueprint $table) {
            $table->string('serie', 128);
            $table->integer('correlativo');
            $table->integer('ultimo_correlativo');
        });
    }
};
