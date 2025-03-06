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
    Schema::create('product_types', function (Blueprint $table) {
      $table->id('id_product_types');
      $table->string('code', 8)->index('product_types_code_IDX')->unique();
      $table->string('name', 256)->index('product_types_name_IDX');
      $table->unsignedBigInteger('parent_id')->nullable()->index('product_types_parent_id_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->nullable();
      $table->foreign('parent_id')->references('id_product_types')->on('product_types');
    });

    // CREATE TRIIGER FOR product_types
    DB::unprepared('
      CREATE TRIGGER tr_bi_product_types BEFORE INSERT ON product_types
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_product_types BEFORE UPDATE ON product_types
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
    Schema::dropIfExists('product_types');
  }
};
