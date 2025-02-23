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
            $table->unsignedBigInteger('id_almacen_origen', false)->nullable()->change();
            $table->unsignedBigInteger('id_almacen_destino', false)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operacion', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_almacen_origen', false);
            $table->unsignedBigInteger('id_almacen_destino', false)->nullable();
        });
    }
};
