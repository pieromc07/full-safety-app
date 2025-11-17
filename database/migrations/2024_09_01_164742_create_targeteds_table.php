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
    Schema::create('targeteds', function (Blueprint $table) {
      $table->id('id_targeteds');
      $table->string('name', 128)->index('targeteds_name_IDX');
      $table->text('image')->nullable();
      $table->unsignedBigInteger('id_load_types')->nullable();
      $table->unsignedBigInteger('targeted_id')->nullable();
      $table->foreign('targeted_id')->references('id_targeteds')->on('targeteds');
      $table->foreign('id_load_types')->references('id_load_types')->on('load_types');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR targeteds
    DB::unprepared('
      CREATE TRIGGER tr_bi_targeteds BEFORE INSERT ON targeteds
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_targeteds BEFORE UPDATE ON targeteds
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
    Schema::dropIfExists('targeteds');
  }
};
