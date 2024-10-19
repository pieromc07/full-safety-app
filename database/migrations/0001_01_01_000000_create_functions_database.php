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
    // CREATE FUNCTION CUID_19D_B10 RETURNS BIGINT UNSIGNED
    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_19D_B10;
      CREATE DEFINER = CURRENT_USER FUNCTION CUID_19D_B10() RETURNS BIGINT UNSIGNED
        DETERMINISTIC
      BEGIN
        RETURN (((SYSDATE() + 0) * 100000) + MOD(UUID_SHORT(), 100000));
      END;
    ');

    // CREATE FUNCTION CUID_13D_B36 RETURNS CHAR(13) CHARSET
    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_13D_B36;
      CREATE DEFINER = CURRENT_USER FUNCTION CUID_13D_B36() RETURNS CHAR(13) CHARSET utf8mb4
        DETERMINISTIC
      BEGIN
        RETURN CONV(CUID_19D_B10(), 10, 36);
      END;
    ');

    // CREATE FUNCTION CUID_TO_DATETIME RETURNS DATETIME
    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_TO_DATETIME;
      CREATE DEFINER= CURRENT_USER FUNCTION CUID_TO_DATETIME(p_cuid BIGINT UNSIGNED) RETURNS DATETIME(6)
        DETERMINISTIC
      BEGIN
        RETURN STR_TO_DATE(p_cuid, "%Y%m%d%H%i%s%f");
      END;
    ');

    // CREATE FUNCTION DATETIME_TO_CUID RETURNS BIGINT UNSIGNED
    DB::unprepared('
      DROP FUNCTION IF EXISTS DATETIME_TO_CUID;
      CREATE DEFINER= CURRENT_USER FUNCTION DATETIME_TO_CUID(p_datetime DATETIME(6)) RETURNS BIGINT UNSIGNED
        DETERMINISTIC
      BEGIN
        RETURN (p_datetime + 0) * 100000;
      END;
    ');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // DROP FUNCTION FOR DATABASE
    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_19D_B10
    ');

    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_13D_B36
    ');

    DB::unprepared('
      DROP FUNCTION IF EXISTS CUID_TO_DATETIME
    ');

    DB::unprepared('
      DROP FUNCTION IF EXISTS DATETIME_TO_CUID
    ');
  }
};
