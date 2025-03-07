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
    Schema::create('products', function (Blueprint $table) {
      $table->id('id_products');
      $table->string('name', 128)->index('products_name_IDX');
      $table->string('number_onu', 16)->index('products_number_onu_IDX');
      $table->integer('health')->default(0);
      $table->integer('flammability')->default(0);
      $table->integer('reactivity')->default(0);
      $table->integer('special')->default(0);
      $table->unsignedBigInteger('id_product_types')->index('products_id_product_types_IDX');
      $table->unsignedBigInteger('id_unit_types')->index('products_id_unit_types_IDX');
      $table->unsignedBigInteger('id_users_inserted')->index('products_id_users_inserted_IDX');
      $table->unsignedBigInteger('id_users_updated')->index('products_id_users_updated_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
      $table->foreign('id_product_types')->references('id_product_types')->on('product_types');
      $table->foreign('id_unit_types')->references('id_unit_types')->on('unit_types');
      $table->foreign('id_users_inserted')->references('id_users')->on('users');
      $table->foreign('id_users_updated')->references('id_users')->on('users');
    });

    // CREATE TRIIGER FOR products
    DB::unprepared('
      CREATE TRIGGER tr_bi_products BEFORE INSERT ON products
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_products BEFORE UPDATE ON products
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
    Schema::dropIfExists('products');
  }
};
