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
      $table->string('marca', 25)->nullable();
      $table->string('familia', 40)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('productos', function (Blueprint $table) {
      $table->dropColumn('marca');
      $table->dropColumn('familia');
    });
  }
};
