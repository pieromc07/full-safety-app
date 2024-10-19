<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('inspection_types', function (Blueprint $table) {
      $table->id();
      $table->string('name', 64)->index('inspection_types_name_IDX');
      $table->string('description')->nullable()->index('inspection_types_description_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
    });

    // CREATE TRIGGER FOR inspection_types
    DB::unprepared('
      CREATE TRIGGER tr_bi_inspection_types BEFORE INSERT ON inspection_types
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_inspection_types BEFORE UPDATE ON inspection_types
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('inspection_types');
  }
};
