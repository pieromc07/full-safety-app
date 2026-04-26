<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('targeted_rels_load_types', function (Blueprint $table) {
      $table->id('id_targeted_rels_load_types');
      $table->unsignedBigInteger('id_targeteds');
      $table->unsignedBigInteger('id_load_types');
      $table->foreign('id_targeteds')->references('id_targeteds')->on('targeteds');
      $table->foreign('id_load_types')->references('id_load_types')->on('load_types');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    DB::unprepared('
      CREATE TRIGGER tr_bi_targeted_rels_load_types BEFORE INSERT ON targeted_rels_load_types
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_targeted_rels_load_types BEFORE UPDATE ON targeted_rels_load_types
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');
  }

  public function down(): void
  {
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_targeted_rels_load_types');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_targeted_rels_load_types');
    Schema::dropIfExists('targeted_rels_load_types');
  }
};
