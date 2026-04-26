<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      if (Schema::hasColumn('users', 'token')) {
        $table->dropColumn('token');
      }
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      if (!Schema::hasColumn('users', 'token')) {
        $table->text('token')->nullable()->after('password');
      }
    });
  }
};
