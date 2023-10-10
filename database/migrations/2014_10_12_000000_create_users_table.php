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
    Schema::create('usuarios', function (Blueprint $table) {
      $table->id('usuario_id');
      $table->string('nombre');
      $table->string('correo')->unique();
      $table->string('password');
      $table->string('status')->default('Activo')->comment('Activo, Baja');
      $table->boolean('mostrar')->default(true);
      $table->boolean('impresora_predeterminada')->default(false);
      $table->string('impresora_nombre', 200)->nullable();
      $table->string('impresora_puerto', 10)->nullable();
      $table->ipAddress('impresora_ip')->nullable();
      $table->unsignedBigInteger('registro_autor_id')->nullable();
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
    Schema::table('usuarios', function (Blueprint $table) {
      // Cambiar los nombres de nuevo en la migración de rollback
      $table->renameColumn('registro_fecha', 'created_at');
      $table->renameColumn('actualizacion_fecha', 'updated_at');
    });
    Schema::dropIfExists('usuarios');
  }
};
