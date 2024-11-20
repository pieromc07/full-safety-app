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
    Schema::create('gps_controls', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->time('hour');
      $table->unsignedBigInteger('checkpoint_id');
      $table->unsignedBigInteger('supplier_enterprise_id');
      $table->unsignedBigInteger('transport_enterprise_id');
      $table->integer('option')->nullable();
      $table->integer('state')->nullable();
      $table->text('observation')->nullable();
      $table->text('photo_one')->nullable();
      $table->text('photo_two')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->foreign('checkpoint_id')->references('id')->on('check_points');
      $table->foreign('supplier_enterprise_id')->references('id')->on('enterprises');
      $table->foreign('transport_enterprise_id')->references('id')->on('enterprises');
    });

    DB::unprepared('
    CREATE TRIGGER tr_bi_gps_controls BEFORE INSERT ON gps_controls
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');

    DB::unprepared('
    CREATE TRIGGER tr_bu_gps_controls BEFORE UPDATE ON gps_controls
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_gps_controls');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_gps_controls');
    Schema::dropIfExists('g_p_s_controls');
  }
};
