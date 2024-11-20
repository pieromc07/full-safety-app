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
      $table->id();
      $table->integer('correlative')->index('inspections_correlative_IDX');
      $table->integer('year')->index('inspections_year_IDX')->default(date('Y'));
      $table->string('folio', 128)->index('inspections_folio_IDX');
      $table->date('date')->index('inspections_date_IDX');
      $table->time('hour')->index('inspections_hour_IDX');
      $table->unsignedBigInteger('inspection_type_id');
      $table->unsignedBigInteger('supplier_enterprise_id');
      $table->unsignedBigInteger('transport_enterprise_id');
      $table->unsignedBigInteger('checkpoint_id');
      $table->unsignedBigInteger('targeted_id');
      $table->unsignedBigInteger('user_id')->index('inspections_user_IDX');
      $table->text('observation')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->foreign('inspection_type_id')->references('id')->on('inspection_types');
      $table->foreign('supplier_enterprise_id')->references('id')->on('enterprises');
      $table->foreign('transport_enterprise_id')->references('id')->on('enterprises');
      $table->foreign('checkpoint_id')->references('id')->on('check_points');
      $table->foreign('targeted_id')->references('id')->on('targeteds');
      $table->foreign('user_id')->references('id')->on('users');
    });

    Schema::create('inspection_convoys', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('inspection_id');
      $table->integer('convoy')->nullable()->index('inspections_convoy_IDX');
      $table->tinyInteger('convoy_status')->nullable()->index('inspections_convoy_status_IDX')->comment('1: Bajada, 2: Subida');
      $table->integer('quantity_light_units')->nullable()->index('inspections_quantity_light_units_IDX');
      $table->integer('quantity_heavy_units')->nullable()->index('inspections_quantity_heavy_units_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->foreign('inspection_id')->references('id')->on('inspections');
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

    // CREATE TRIGGER FOR inspection_convoys
    DB::unprepared('
    CREATE TRIGGER tr_bi_inspection_convoys BEFORE INSERT ON inspection_convoys
    FOR EACH ROW
    BEGIN
      SET NEW.cuid_inserted = CUID_19D_B10();
      SET NEW.cuid_updated = CUID_19D_B10();
    END
');

    DB::unprepared('
        CREATE TRIGGER tr_bu_inspection_convoys BEFORE UPDATE ON inspection_convoys
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
