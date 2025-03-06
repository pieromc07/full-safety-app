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
    Schema::create('evidence_rels_inspections', function (Blueprint $table) {
      $table->id('id_evidence_rels_inspections');
      $table->unsignedBigInteger('id_evidences');
      $table->unsignedBigInteger('id_inspections');
      $table->integer('state')->comment('1: Conforme, 2: No conforme, 3: Oportunidad de mejora');
      $table->text('evidence_one')->nullable();
      $table->text('evidence_two')->nullable();
      $table->text('observations')->nullable();
      $table->string('waiting_time', 65)->nullable();
      $table->foreign('id_evidences')->references('id_evidences')->on('evidences');
      $table->foreign('id_inspections')->references('id_inspections')->on('inspections');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    DB::unprepared('
            CREATE TRIGGER tr_bi_evidence_rels_inspections BEFORE INSERT ON evidence_rels_inspections
            FOR EACH ROW
            BEGIN
                SET NEW.cuid_inserted = CUID_19D_B10();
                SET NEW.cuid_updated = CUID_19D_B10();
            END
        ');

    DB::unprepared('
            CREATE TRIGGER tr_bu_evidence_rels_inspections BEFORE UPDATE ON evidence_rels_inspections
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
    Schema::dropIfExists('evidence_rels_inspections');
  }
};
