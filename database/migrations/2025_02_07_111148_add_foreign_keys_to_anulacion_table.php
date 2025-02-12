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
        Schema::table('anulacion', function (Blueprint $table) {
            $table->foreign(['id_comprobante_pago'], 'Relation_80')->references(['id_comprobante_pago'])->on('comprobante_pago')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anulacion', function (Blueprint $table) {
            $table->dropForeign('Relation_80');
        });
    }
};
