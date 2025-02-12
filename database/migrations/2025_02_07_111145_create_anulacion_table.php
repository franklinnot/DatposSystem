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
        Schema::create('anulacion', function (Blueprint $table) {
            $table->id('id_anulacion');
            $table->text('motivo')->nullable();
            $table->dateTime('fecha_anulacion');
            $table->unsignedBigInteger('id_comprobante_pago', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anulacion');
    }
};
