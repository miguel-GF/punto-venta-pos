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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('cliente_id');
            $table->string('nombre_comercial', 200);
            $table->string('telefono', 15)->nullable();
            $table->text('eslogan')->nullable();
            $table->string('correo')->nullable();
            $table->boolean('publico_general')->default(false);
            $table->string('status', 12)->default('Activo')->comment('Activo, Baja');
            // Datos fiscales
            $table->string('tipo_persona', 6)->nullable();
            $table->string('pais', 30)->nullable()->default('México');
            $table->string('pais_clave', 5)->nullable()->default('MEX');
            $table->string('razon_social', 230)->nullable();
            $table->string('rfc', 15)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->text('domicilio_fiscal')->nullable();
            $table->string('regimen_fiscal_clave', 5)->nullable();
            $table->string('regimen_fiscal_nombre', 150)->nullable();
            $table->string('cfdi_clave', 5)->nullable();
            $table->string('cfdi_nombre', 150)->nullable();
            $table->string('correo_fiscal', 200)->nullable();
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
        Schema::table('clientes', function (Blueprint $table) {
            // Cambiar los nombres de nuevo en la migración de rollback
            $table->renameColumn('registro_fecha', 'created_at');
            $table->renameColumn('actualizacion_fecha', 'updated_at');
        });
        Schema::dropIfExists('clientes');
    }
};
