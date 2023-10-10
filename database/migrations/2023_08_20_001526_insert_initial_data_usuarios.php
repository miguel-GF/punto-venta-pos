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
    DB::table('usuarios')->insert([
      [
        'nombre' => 'System',
        'correo' => 'system@sistema-venta-pos.com',
        'password' => md5('@1234@'),
        'mostrar' => 0,
        'impresora_predeterminada' => true,
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
