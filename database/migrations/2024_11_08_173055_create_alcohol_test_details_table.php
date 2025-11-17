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
    Schema::create('alcohol_test_details', function (Blueprint $table) {
      $table->id('id_alcohol_test_details');
      $table->unsignedBigInteger('id_alcohol_tests');
      $table->unsignedBigInteger('id_employees');
      $table->decimal('result', 5, 2)->nullable();
      $table->integer('state')->nullable();
      $table->text('photo_one')->nullable();
      $table->text('photo_one_uri')->nullable();
      $table->text('photo_two')->nullable();
      $table->text('photo_two_uri')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_alcohol_tests')->references('id_alcohol_tests')->on('alcohol_tests');
      $table->foreign('id_employees')->references('id_employees')->on('employees');
    });

    DB::unprepared('
    CREATE TRIGGER tr_bi_alcohol_test_details BEFORE INSERT ON alcohol_test_details
    FOR EACH ROW
    BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
    END;
  ');

    DB::unprepared('
    CREATE TRIGGER tr_bu_alcohol_test_details BEFORE UPDATE ON alcohol_test_details
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
    Schema::dropIfExists('alcohol_test_details');
  }
};
