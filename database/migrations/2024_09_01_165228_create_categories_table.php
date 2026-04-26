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
      // Solo categorías raíz (parent_id = null) llevan el par dirigido↔tipo de inspección.
      // Las subcategorías heredan el par de su padre.
      // FK se agrega en la migración de targeted_rels_inspections (esa tabla aún no existe).
      $table->unsignedBigInteger('id_targeted_rels_inspections')->nullable();
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
