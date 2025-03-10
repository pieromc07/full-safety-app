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
    Schema::create('unit_movements', function (Blueprint $table) {
      $table->id('id_unit_movements');
      $table->dateTime('date');
      $table->time('time');
      $table->time('time_arrival')->nullable();
      $table->time('time_departure')->nullable();
      $table->unsignedBigInteger('id_checkpoints');
      $table->integer('convoy');
      $table->tinyInteger('convoy_state')->comment('1: Cargado, 2: Vacio');
      $table->integer('heavy_vehicle');
      $table->integer('light_vehicle');
      $table->tinyInteger('direction')->comment('1: Subida, 2: Bajada');
      $table->unsignedBigInteger('id_supplier_enterprises');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->unsignedBigInteger('id_products');
      $table->unsignedBigInteger('id_users');
      $table->foreign('id_checkpoints')->references('id_checkpoints')->on('checkpoints');
      $table->foreign('id_supplier_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
      $table->foreign('id_products')->references('id_products')->on('products');
      $table->foreign('id_users')->references('id_users')->on('users');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    DB::unprepared('
    CREATE TRIGGER tr_bi_unit_movements BEFORE INSERT ON unit_movements
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');

    DB::unprepared('
    CREATE TRIGGER tr_bu_unit_movements BEFORE UPDATE ON unit_movements
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
    Schema::dropIfExists('unit_movements');
  }
};
