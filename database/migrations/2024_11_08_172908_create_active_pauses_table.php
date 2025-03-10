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
    Schema::create('active_pauses', function (Blueprint $table) {
      $table->id('id_active_pauses');
      $table->date('date');
      $table->time('hour');
      $table->unsignedBigInteger('id_checkpoints');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->integer('participants')->nullable();
      $table->text('photo_one')->nullable();
      $table->text('photo_two')->nullable();
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
      CREATE TRIGGER tr_bi_active_pauses BEFORE INSERT ON active_pauses
      FOR EACH ROW
      BEGIN
          SET NEW.cuid_inserted = CUID_19D_B10();
          SET NEW.cuid_updated = CUID_19D_B10();
      END;
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_active_pauses BEFORE UPDATE ON active_pauses
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
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_active_pauses');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_active_pauses');
    Schema::dropIfExists('active_pauses');
  }
};
