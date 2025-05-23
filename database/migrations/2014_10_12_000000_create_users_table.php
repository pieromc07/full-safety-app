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
    Schema::create('users', function (Blueprint $table) {
      $table->id('id_users');
      $table->string('fullname', 128)->index('users_fullname_IDX');
      $table->string('username', 16)->unique()->index('users_username_IDX');
      $table->string('password', 256)->index('users_password_IDX');
      $table->text('token')->nullable();
      $table->tinyInteger('status')->default(1)->index('users_status_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->nullable();
    });

    // CREATE TRIGGER FOR users
    DB::unprepared('
      CREATE TRIGGER tr_bi_users BEFORE INSERT ON users
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_users BEFORE UPDATE ON users
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
    Schema::dropIfExists('users');
  }
};
