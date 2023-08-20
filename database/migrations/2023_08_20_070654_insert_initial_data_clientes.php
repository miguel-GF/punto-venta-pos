<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('clientes')->insert([
            [
                'nombre_comercial' => 'Público en General',
                'razon_social' => 'PUBLICO EN GENERAL',
                'rfc' => 'XAXX010101000',
                'tipo_persona' => 'fisica',
                'publico_general' => true,
                'codigo_postal' => '72000',
                'cfdi_clave' => 'G03',
                'cfdi_nombre' => 'Gastos en general',
                'registro_autor_id' => 1,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
