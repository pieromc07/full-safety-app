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
    Schema::create('companies', function (Blueprint $table) {
      $table->id('id_companies');
      $table->string('ruc', 11)->index('companies_ruc_IDX');
      $table->string('name', 128)->index('companies_name_IDX');
      $table->string('commercial_name', 128)->index('companies_commercial_name_IDX');
      $table->string('logo', 512)->index('company_logo_IDX');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });
    // CREATE TRIIGER FOR companies
    DB::unprepared('
     CREATE TRIGGER tr_bi_companies BEFORE INSERT ON companies
     FOR EACH ROW
     BEGIN
       SET NEW.cuid_inserted = CUID_19D_B10();
       SET NEW.cuid_updated = CUID_19D_B10();
     END
   ');

    DB::unprepared('
     CREATE TRIGGER tr_bu_companies BEFORE UPDATE ON companies
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
    Schema::dropIfExists('companies');
  }
};
