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
    Schema::create('daily_dialogs', function (Blueprint $table) {
      $table->id('id_daily_dialogs');
      $table->date('date')->index('daily_dialogs_date_IDX');
      $table->time('hour');
      $table->unsignedBigInteger('id_checkpoints');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->text('topic');
      $table->integer('participants')->nullable();
      $table->text('photo_one')->nullable();
      $table->text('photo_two')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_checkpoints')->references('id_checkpoints')->on('checkpoints');
      $table->foreign('id_supplier_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
    });

    DB::unprepared('
      CREATE TRIGGER tr_bi_daily_dialogs BEFORE INSERT ON daily_dialogs
      FOR EACH ROW
      BEGIN
          SET NEW.cuid_inserted = CUID_19D_B10();
          SET NEW.cuid_updated = CUID_19D_B10();
      END;
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_daily_dialogs BEFORE UPDATE ON daily_dialogs
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

    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_daily_dialogs');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_daily_dialogs');
    Schema::dropIfExists('daily_dialogs');
  }
};
