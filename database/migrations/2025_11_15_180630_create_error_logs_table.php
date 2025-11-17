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
    Schema::create('error_logs', function (Blueprint $table) {
      $table->id('id_error_logs');
      $table->dateTime('date')->nullable();
      $table->string('type')->nullable();
      $table->string('source')->nullable();
      $table->text('message')->nullable();
      $table->longText('trace')->nullable();
      $table->json('data')->nullable();
      $table->unsignedBigInteger('id_users_inserted')->nullable();
      $table->unsignedBigInteger('id_users_updated')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    DB::unprepared('
        CREATE TRIGGER tr_bi_error_logs BEFORE INSERT ON error_logs
        FOR EACH ROW
        BEGIN
            SET NEW.cuid_inserted = CUID_19D_B10();
            SET NEW.cuid_updated = CUID_19D_B10();
        END;
      ');

    DB::unprepared('
        CREATE TRIGGER tr_bu_error_logs BEFORE UPDATE ON error_logs
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
    Schema::dropIfExists('error_logs');
  }
};
