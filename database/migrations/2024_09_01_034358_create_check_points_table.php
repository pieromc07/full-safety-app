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
    Schema::create('check_points', function (Blueprint $table) {
      $table->id();
      $table->string('name', 128)->index('check_points_name_IDX');
      $table->string('description', 256)->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
    });

    // CREATE TRIGGER FOR check_points
    DB::unprepared('
            CREATE TRIGGER tr_bi_check_points BEFORE INSERT ON check_points
            FOR EACH ROW
            BEGIN
                SET NEW.cuid_inserted = CUID_19D_B10();
                SET NEW.cuid_updated = CUID_19D_B10();
            END
        ');

    DB::unprepared('
            CREATE TRIGGER tr_bu_check_points BEFORE UPDATE ON check_points
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
    Schema::dropIfExists('check_points');
  }
};
