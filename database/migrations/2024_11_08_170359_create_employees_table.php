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
    Schema::create('employees', function (Blueprint $table) {
      $table->id('id_employees');
      $table->string('document', 16)->index('employees_document_IDX');
      $table->string('name', 50)->index('employees_name_IDX');
      $table->string('lastname', 50)->index('employees_lastname_IDX');
      $table->string('fullname', 150)->index('employees_fullname_IDX');
      $table->unsignedBigInteger('id_transport_enterprises');
      $table->foreign('id_transport_enterprises')->references('id_enterprises')->on('enterprises');
      $table->unsignedBigInteger('cuid_inserted')->unique();
      $table->unsignedBigInteger('cuid_updated')->unique();
      $table->unsignedBigInteger('cuid_deleted')->unique()->nullable();
    });

    // Crear los triggers para insertar y actualizar en la tabla `employees`
    DB::unprepared('
            CREATE TRIGGER tr_bi_employees BEFORE INSERT ON employees
            FOR EACH ROW
            BEGIN
                SET NEW.cuid_inserted = CUID_19D_B10();
                SET NEW.cuid_updated = CUID_19D_B10();
            END;
        ');

    DB::unprepared('
            CREATE TRIGGER tr_bu_employees BEFORE UPDATE ON employees
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
    // Eliminar triggers antes de eliminar la tabla
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bi_employees');
    DB::unprepared('DROP TRIGGER IF EXISTS tr_bu_employees');

    Schema::dropIfExists('employees');
  }
};
