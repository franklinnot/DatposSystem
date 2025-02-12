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
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropForeign('PagoTarifa_Empresa');
            $table->dropColumn('id_pago_tarifa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pago_tarifa', false)->nullable();
            $table->foreign(['id_pago_tarifa'], 'PagoTarifa_Empresa')
                ->references(['id_pago_tarifa'])
                ->on('pago_tarifa')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }
};
