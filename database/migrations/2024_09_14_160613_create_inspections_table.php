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

    Schema::create('inspections', function (Blueprint $table) {
      $table->id('id_inspections');
      $table->integer('correlative')->index('inspections_correlative_IDX');
      $table->integer('year')->index('inspections_year_IDX')->default(date('Y'));
      $table->string('folio', 128)->index('inspections_folio_IDX');
      $table->date('date')->index('inspections_date_IDX');
      $table->time('hour')->index('inspections_hour_IDX');
      $table->unsignedBigInteger('id_inspection_types');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->unsignedBigInteger('id_checkpoints');
      $table->unsignedBigInteger('id_targeteds');
      $table->unsignedBigInteger('id_users');
      $table->text('observation')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_inspection_types')->references('id_inspection_types')->on('inspection_types');
      $table->foreign('id_supplier_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_checkpoints')->references('id_checkpoints')->on('checkpoints');
      $table->foreign('id_targeteds')->references('id_targeteds')->on('targeteds');
      $table->foreign('id_users')->references('id_users')->on('users');
    });

    // CREATE TRIGGER FOR inspections
    DB::unprepared('
    CREATE TRIGGER tr_bi_inspections BEFORE INSERT ON inspections
    FOR EACH ROW
    BEGIN
      DECLARE last_correlative INT DEFAULT 0;
      -- Obtener el último correlativo para el año correspondiente
      SELECT IFNULL(MAX(correlative), 0) INTO last_correlative FROM inspections WHERE year = YEAR(NEW.date);
      -- Asignar el año basado en la fecha de la nueva inspección
      SET NEW.year = YEAR(NEW.date);
      -- Incrementar el correlativo y asignarlo
      SET NEW.correlative = last_correlative + 1;
      -- Generar el folio concatenando el correlativo con el año
      SET NEW.folio = CONCAT(LPAD(NEW.correlative, 4, "0"), "-", NEW.year);
      -- Asignar CUIDs para los campos cuid_inserted y cuid_updated
      SET NEW.cuid_inserted = CUID_19D_B10();
      SET NEW.cuid_updated = CUID_19D_B10();
    END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_inspections BEFORE UPDATE ON inspections
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
    Schema::dropIfExists('inspections');
  }
};
