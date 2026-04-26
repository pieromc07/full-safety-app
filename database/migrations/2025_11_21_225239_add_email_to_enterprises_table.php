<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('enterprises', 'email')) {
            return;
        }
        Schema::table('enterprises', function (Blueprint $table) {
            $table->string('email')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: la columna `email` se crea en la migración base de enterprises.
    }
};
