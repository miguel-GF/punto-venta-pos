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
      Schema::create('ventas_archivos', function (Blueprint $table) {
        $table->id('venta_archivo_id');
        $table->unsignedBigInteger('venta_id');
        $table->binary('archivo');
        $table->text('nombre');
        $table->string('extension', 5);
        $table->foreign('venta_id')->references('venta_id')->on('ventas');
        $table->string('status')->default('Activo');
        $table->timestamps();
        // Cambiar nombres de campos de fecha
        $table->renameColumn('created_at', 'registro_fecha');
        $table->renameColumn('updated_at', 'actualizacion_fecha');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('ventas_archivos');
    }
};
