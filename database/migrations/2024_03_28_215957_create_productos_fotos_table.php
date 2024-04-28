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
    Schema::create('productos_fotos', function (Blueprint $table) {
      $table->id('producto_foto_id');
      $table->unsignedBigInteger('producto_id');
      $table->binary('archivo');
      $table->text('nombre');
      $table->string('extension', 5);
      $table->foreign('producto_id')->references('producto_id')->on('productos');
      $table->string('status')->default('Activo');
      $table->string('tipo', 10)->default('Normal')->comment('Valores: Normal, Thumnbnail');
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
    Schema::dropIfExists('productos_fotos');
  }
};
