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
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('password');
            $table->string('status')->default('Activo');
            $table->boolean('mostrar')->default(true);
            $table->integer('registro_autor_id')->nullable();
            $table->integer('actualizacion_autor_id')->nullable();
            $table->timestamps();

             // Cambiar nombres de campos de fecha
             $table->renameColumn('created_at', 'fecha_registro');
             $table->renameColumn('updated_at', 'fecha_actualizacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Cambiar los nombres de nuevo en la migración de rollback
            $table->renameColumn('fecha_registro', 'created_at');
            $table->renameColumn('fecha_actualizacion', 'updated_at');
        });
        Schema::dropIfExists('usuarios');
    }
};
