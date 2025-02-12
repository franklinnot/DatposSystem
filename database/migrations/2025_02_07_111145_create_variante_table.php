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
        Schema::create('variante', function (Blueprint $table) {
            $table->id('id_variante');
            $table->string('nombre', 128);
            $table->integer('estado');
            $table->unsignedBigInteger('id_producto', false);
            $table->unsignedBigInteger('id_empresa', false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variante');
    }
};
