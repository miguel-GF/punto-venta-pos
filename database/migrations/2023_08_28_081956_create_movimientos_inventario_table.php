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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('movimiento_inventario_id');
            $table->unsignedBigInteger('producto_id');
            $table->string('tipo', 15);
            $table->double('cantidad');
            $table->unsignedBigInteger('registro_autor_id');
            $table->timestamps();
            $table->foreign('producto_id')->references('producto_id')->on('productos');
            $table->foreign('registro_autor_id')->references('usuario_id')->on('usuarios');

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
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            // Cambiar los nombres de nuevo en la migración de rollback
            $table->renameColumn('registro_fecha', 'created_at');
            $table->renameColumn('actualizacion_fecha', 'updated_at');
        });
        Schema::dropIfExists('movimientos_inventario');
    }
};
