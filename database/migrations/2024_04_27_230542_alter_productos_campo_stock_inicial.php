<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('productos', function (Blueprint $table) {
      $table->boolean('con_existencia_inicial')->default(false);
    });
    DB::table('productos')
      ->where('existencia', '>', 0)
      ->update(['con_existencia_inicial' => true]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('productos', function (Blueprint $table) {
      $table->dropColumn('con_existencia_inicial');
    });
  }
};
