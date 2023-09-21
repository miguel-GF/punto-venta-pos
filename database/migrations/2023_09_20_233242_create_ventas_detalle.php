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
    Schema::create('ventas_detalle', function (Blueprint $table) {
      $table->id('venta_detalle_id');
      $table->unsignedBigInteger('venta_id');
      $table->unsignedBigInteger('producto_id');
      $table->decimal('total');
      $table->decimal('cantidad');
      $table->decimal('precio_unitario');
      $table->unsignedBigInteger('registro_autor_id');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->foreign('venta_id')->references('venta_id')->on('ventas');
      $table->foreign('producto_id')->references('producto_id')->on('productos');
      $table->foreign('registro_autor_id')->references('usuario_id')->on('usuarios');
      $table->foreign('actualizacion_autor_id')->references('usuario_id')->on('usuarios');
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
    Schema::table('ventas_detalle', function (Blueprint $table) {
      // Cambiar los nombres de nuevo en la migración de rollback
      $table->renameColumn('registro_fecha', 'created_at');
      $table->renameColumn('actualizacion_fecha', 'updated_at');
    });
    Schema::dropIfExists('ventas_detalle');
  }
};
