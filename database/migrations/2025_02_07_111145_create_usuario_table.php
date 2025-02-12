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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();
            $table->string('nombre', 255);
            $table->string('direccion', 255)->nullable();
            $table->binary('foto')->nullable();
            $table->integer('estado');
            $table->unsignedBigInteger('id_rol', false);
            $table->unsignedBigInteger('id_sucursal', false);
            $table->unsignedBigInteger('id_almacen', false)->nullable();
            $table->unsignedBigInteger('id_empresa', false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
