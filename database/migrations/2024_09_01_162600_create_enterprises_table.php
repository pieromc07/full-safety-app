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
    Schema::create('enterprises', function (Blueprint $table) {
      $table->id('id_enterprises');
      $table->string('name', 128)->index('enterprises_name_IDX');
      $table->string('ruc', 11)->index('enterprises_ruc_IDX');
      $table->string('email', 128)->nullable();
      $table->string('phone', 20)->nullable();
      $table->string('address', 256)->nullable();
      $table->string('contact_name', 128)->nullable();
      $table->string('website', 256)->nullable();
      $table->text('image')->nullable();
      $table->unsignedBigInteger('id_enterprise_types');
      $table->foreign('id_enterprise_types')->references('id_enterprise_types')->on('enterprise_types');
      $table->unique(['ruc', 'id_enterprise_types'], 'enterprises_ruc_id_enterprise_types_UK');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // CREATE TRIGGER FOR enterprises
    DB::unprepared('
      CREATE TRIGGER tr_bi_enterprises BEFORE INSERT ON enterprises
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_inserted = CUID_19D_B10();
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    DB::unprepared('
      CREATE TRIGGER tr_bu_enterprises BEFORE UPDATE ON enterprises
      FOR EACH ROW
      BEGIN
        SET NEW.cuid_updated = CUID_19D_B10();
      END
    ');

    // Ahora que enterprises existe, agregar la FK pendiente en users.id_enterprises
    Schema::table('users', function (Blueprint $table) {
      $table->foreign('id_enterprises')->references('id_enterprises')->on('enterprises')->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropForeign(['id_enterprises']);
    });
    Schema::dropIfExists('enterprises');
  }
};
