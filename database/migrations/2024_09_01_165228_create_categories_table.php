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
    Schema::create('categories', function (Blueprint $table) {
      $table->id('id_categories');
      $table->string('name', 64)->index('categories_name_IDX');
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->foreign('parent_id')->references('id_categories')->on('categories');
      $table->unsignedBigInteger('id_targeteds')->nullable();
      $table->unsignedBigInteger('id_inspection_types')->nullable();
      $table->foreign('id_targeteds')->references('id_targeteds')->on('targeteds');
      $table->foreign('id_inspection_types')->references('id_inspection_types')->on('inspection_types');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR categories
    DB::unprepared('
      CREATE TRIGGER tr_bi_categories BEFORE INSERT ON categories
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_categories BEFORE UPDATE ON categories
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
    Schema::dropIfExists('categories');
  }
};
