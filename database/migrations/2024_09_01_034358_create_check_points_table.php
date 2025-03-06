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
    Schema::create('checkpoints', function (Blueprint $table) {
      $table->id('id_checkpoints');
      $table->string('name', 128)->index('checkpoints_name_IDX');
      $table->string('description', 256)->index('checkpoints_description_IDX')->nullable();
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR checkpoints
    DB::unprepared('
            CREATE TRIGGER tr_bi_checkpoints BEFORE INSERT ON checkpoints
            FOR EACH ROW
            BEGIN
                SET NEW.cuid_inserted = CUID_19D_B10();
                SET NEW.cuid_updated = CUID_19D_B10();
            END
        ');

    DB::unprepared('
            CREATE TRIGGER tr_bu_checkpoints BEFORE UPDATE ON checkpoints
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
    Schema::dropIfExists('checkpoints');
  }
};
