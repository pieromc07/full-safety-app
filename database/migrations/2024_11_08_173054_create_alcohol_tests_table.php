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
    Schema::create('alcohol_tests', function (Blueprint $table) {
      $table->id('id_alcohol_tests');
      $table->date('date');
      $table->time('hour');
      $table->unsignedBigInteger('id_checkpoints');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->unsignedBigInteger('id_users');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_checkpoints')->references('id_checkpoints')->on('checkpoints');
      $table->foreign('id_supplier_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_users')->references('id_users')->on('users');
    });

    DB::unprepared('
    CREATE TRIGGER tr_bi_alcohol_tests BEFORE INSERT ON alcohol_tests
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');

    DB::unprepared('
    CREATE TRIGGER tr_bu_alcohol_tests BEFORE UPDATE ON alcohol_tests
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
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_alcohol_tests');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_alcohol_tests');
    Schema::dropIfExists('alcohol_tests');
  }
};
