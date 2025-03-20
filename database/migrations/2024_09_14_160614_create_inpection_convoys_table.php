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
    Schema::create('inspection_convoys', function (Blueprint $table) {
      $table->id('id_inspection_convoys');
      $table->unsignedBigInteger('id_inspections');
      $table->integer('convoy')->nullable()->index('inspections_convoy_IDX');
      $table->tinyInteger('convoy_status')->nullable()->index('inspections_convoy_status_IDX')->comment('1: Bajada, 2: Subida');
      $table->integer('quantity_light_units')->nullable()->index('inspections_quantity_light_units_IDX');
      $table->integer('quantity_heavy_units')->nullable()->index('inspections_quantity_heavy_units_IDX');
      $table->unsignedBigInteger('id_products')->nullable()->index('inspections_products_IDX');
      $table->unsignedBigInteger('id_products_two')->nullable()->index('inspections_products_two_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_inspections')->references('id_inspections')->on('inspections');
      $table->foreign('id_products')->references('id_products')->on('products');
      $table->foreign('id_products_two')->references('id_products')->on('products');
    });

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
    Schema::dropIfExists('inpection_convoys');
  }
};
