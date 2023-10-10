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
    Schema::create('sucursales', function (Blueprint $table) {
      $table->id('sucursal_id');
      $table->integer('folio');
      $table->string('clave', 10);
      $table->string('nombre', 100);
      $table->text('direccion');
      $table->string('telefono', 15)->nullable();
      $table->string('rfc', 15)->nullable();
      $table->string('status', 12)->default('Activo')->comment('Activo');
      $table->boolean('default')->default(true);
      $table->unsignedBigInteger('registro_autor_id');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
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
    Schema::table('sucursales', function (Blueprint $table) {
      // Cambiar los nombres de nuevo en la migración de rollback
      $table->renameColumn('registro_fecha', 'created_at');
      $table->renameColumn('actualizacion_fecha', 'updated_at');
    });
    Schema::dropIfExists('sucursales');
  }
};
