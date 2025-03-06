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
    Schema::create('targeted_rels_inspections', function (Blueprint $table) {
      $table->id('id_targeted_rels_inspections');
      $table->unsignedBigInteger('id_targeteds');
      $table->unsignedBigInteger('id_inspection_types');
      $table->foreign('id_targeteds')->references('id_targeteds')->on('targeteds');
      $table->foreign('id_inspection_types')->references('id_inspection_types')->on('inspection_types');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR targeted_rels_inspections
    DB::unprepared('
      CREATE TRIGGER tr_bi_targeted_rels_inspections BEFORE INSERT ON targeted_rels_inspections
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_targeted_rels_inspections BEFORE UPDATE ON targeted_rels_inspections
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
    Schema::dropIfExists('targeted_rels_inspections');
  }
};
