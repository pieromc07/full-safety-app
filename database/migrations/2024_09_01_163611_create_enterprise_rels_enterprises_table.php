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
    Schema::create('enterprise_rels_enterprises', function (Blueprint $table) {
      $table->id('id_enterprise_rels_enterprises');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_supplier_enterprises')->references('id_enterprises')->on('enterprises');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });


    // CREATE TRIGGER FOR enterprise_rels_enterprises
    DB::unprepared('
      CREATE TRIGGER tr_bi_enterprise_rels_enterprises BEFORE INSERT ON enterprise_rels_enterprises
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_enterprise_rels_enterprises BEFORE UPDATE ON enterprise_rels_enterprises
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
    Schema::dropIfExists('enterprise_rels_enterprises');
  }
};
