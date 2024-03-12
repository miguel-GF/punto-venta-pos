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
    Schema::table('movimientos_inventario', function (Blueprint $table) {
      $table->unsignedBigInteger('venta_id')->nullable();
      $table->foreign('venta_id')->references('venta_id')->on('ventas');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('movimientos_inventario', function (Blueprint $table) {
      $table->dropForeign(['venta_id']);
      $table->dropColumn('venta_id');
    });
  }
};
