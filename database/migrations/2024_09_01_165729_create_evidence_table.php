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
    Schema::create('evidence', function (Blueprint $table) {
      $table->id();
      $table->string('name', 128)->index('evidence_name_IDX');
      $table->text('description')->nullable();
      $table->unsignedBigInteger('category_id')->nullable();
      $table->foreign('category_id')->references('id')->on('categories');
      $table->unsignedBigInteger('subcategory_id');
      $table->foreign('subcategory_id')->references('id')->on('categories');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
    });

    // CREATE TRIGGER FOR evidence
    DB::unprepared('
      CREATE TRIGGER tr_bi_evidence BEFORE INSERT ON evidence
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_evidence BEFORE UPDATE ON evidence
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
    Schema::dropIfExists('evidence');
  }
};
