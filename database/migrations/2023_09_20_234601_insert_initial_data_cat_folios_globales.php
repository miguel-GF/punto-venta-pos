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
    DB::table('cat_folios_globales')->insert([
      [
        'tipo' => 'venta',
        'folio' => '10000',
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
