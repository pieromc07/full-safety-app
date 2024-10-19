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
    Schema::create('enterprises', function (Blueprint $table) {
      $table->id();
      $table->string('name', 128)->index('enterprises_name_IDX');
      $table->string('ruc', 11)->index('enterprises_ruc_IDX');
      $table->text('image')->nullable();
      $table->unsignedBigInteger('enterprise_type_id');
      $table->foreign('enterprise_type_id')->references('id')->on('enterprise_types');
      $table->unique(['ruc', 'enterprise_type_id'], 'enterprises_ruc_enterprise_type_id_UNI');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
    });

    // CREATE TRIGGER FOR enterprises
    DB::unprepared('
      CREATE TRIGGER tr_bi_enterprises BEFORE INSERT ON enterprises
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_enterprises BEFORE UPDATE ON enterprises
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
    Schema::dropIfExists('enterprises');
  }
};
