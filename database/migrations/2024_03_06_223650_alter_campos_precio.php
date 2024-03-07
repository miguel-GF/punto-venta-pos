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
      Schema::table('productos', function (Blueprint $table) {
        $table->decimal('precio', 12, 2)->change();
      });
      Schema::table('ventas', function (Blueprint $table) {
        $table->decimal('total', 12, 2)->change();
      });
      Schema::table('ventas_detalle', function (Blueprint $table) {
        $table->decimal('precio_unitario', 12, 2)->change();
        $table->decimal('total', 12, 2)->change();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('productos', function (Blueprint $table) {
        $table->decimal('precio', 8, 2)->change();
      });
      Schema::table('ventas', function (Blueprint $table) {
        $table->decimal('total', 8, 2)->change();
      });
      Schema::table('ventas_detalle', function (Blueprint $table) {
        $table->decimal('precio_unitario', 8, 2)->change();
        $table->decimal('total', 8, 2)->change();
      });
    }
};
