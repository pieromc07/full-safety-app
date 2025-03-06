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
    Schema::create('evidences', function (Blueprint $table) {
      $table->id('id_evidences');
      $table->string('name', 128)->index('evidence_name_IDX');
      $table->text('description')->nullable();
      $table->unsignedBigInteger('id_categories');
      $table->unsignedBigInteger('id_subcategories');
      $table->foreign('id_categories')->references('id_categories')->on('categories');
      $table->foreign('id_subcategories')->references('id_categories')->on('categories');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR evidence
    DB::unprepared('
      CREATE TRIGGER tr_bi_evidences BEFORE INSERT ON evidences
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_evidences BEFORE UPDATE ON evidences
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
    Schema::dropIfExists('evidences');
  }
};
