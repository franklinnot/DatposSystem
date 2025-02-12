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
        Schema::create('detalle_variante', function (Blueprint $table) {
            $table->id('id_detalle_variante');
            $table->string('nombre', 128);
            $table->boolean('estado');
            $table->unsignedBigInteger('id_variante', false);
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_variante');
    }
};
