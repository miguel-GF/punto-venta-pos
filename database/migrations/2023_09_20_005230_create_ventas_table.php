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
    Schema::create('ventas', function (Blueprint $table) {
      $table->id('venta_id');
      $table->unsignedBigInteger('cliente_id');
      $table->integer('folio');
      $table->string('serie_folio', 12);
      $table->decimal('total');
      $table->decimal('cantidad');
      $table->string('status')->default('Activo');
      $table->unsignedBigInteger('registro_autor_id');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->foreign('cliente_id')->references('cliente_id')->on('clientes');
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
    Schema::table('ventas', function (Blueprint $table) {
      // Cambiar los nombres de nuevo en la migración de rollback
      $table->renameColumn('registro_fecha', 'created_at');
      $table->renameColumn('actualizacion_fecha', 'updated_at');
    });
    Schema::dropIfExists('ventas');
  }
};
