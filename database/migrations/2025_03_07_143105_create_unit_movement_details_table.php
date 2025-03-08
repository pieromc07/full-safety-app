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
    Schema::create('unit_movement_details', function (Blueprint $table) {
      $table->id('id_unit_movement_details');
      $table->unsignedBigInteger('id_unit_movements');
      $table->float('weight');
      $table->unsignedBigInteger('id_units');
      $table->unsignedBigInteger('id_products_two')->nullable();
      $table->float('weight_two')->nullable();
      $table->string('referral_guide', 50)->nullable();
      $table->foreign('id_unit_movements')->references('id_unit_movements')->on('unit_movements');
      $table->foreign('id_units')->references('id_units')->on('units');
      $table->foreign('id_products_two')->references('id_products')->on('products');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    DB::unprepared('
    CREATE TRIGGER tr_bi_unit_movement_details BEFORE INSERT ON unit_movement_details
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');

    DB::unprepared('
    CREATE TRIGGER tr_bu_unit_movement_details BEFORE UPDATE ON unit_movement_details
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
    Schema::dropIfExists('unit_movement_details');
  }
};
