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
        Schema::table('usuario', function (Blueprint $table) {
            //
            $table->dropForeign('Relation_47');
            $table->dropColumn('id_almacen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('id_almacen', false)->nullable();
            $table->foreign(['id_almacen'], 'Relation_47')->references(['id_almacen'])->on('almacen')->onUpdate('no action')->onDelete('no action');
        });
    }
};
