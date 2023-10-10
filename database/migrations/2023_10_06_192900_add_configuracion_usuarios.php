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
      Schema::table('usuarios', function (Blueprint $table) {
        $table->boolean('lectura_modo_monitor')->default(true);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('usuarios', function (Blueprint $table) {
        $table->dropColumn('lectura_modo_monitor');
      });
    }
};
