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
        Schema::create('tipo_operacion', function (Blueprint $table) {
            $table->id('id_tipo_operacion');
            $table->string('codigo', 128);
            $table->string('nombre', 128);
            $table->integer('tipo_operacion');
            $table->integer('estado')->nullable();
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_operacion');
    }
};
