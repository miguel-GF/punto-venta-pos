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
    DB::table('sucursales')->insert([
      [
        'folio' => '1',
        'clave' => 'SC001',
        'nombre' => 'Nombre de sucursal',
        'direccion' => 'En algún lugar Av. 001 C.P. 72000',
        'telefono' => '(###) ##-##-###',
        'rfc' => 'XAXX010101000',
        'default' => true,
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
