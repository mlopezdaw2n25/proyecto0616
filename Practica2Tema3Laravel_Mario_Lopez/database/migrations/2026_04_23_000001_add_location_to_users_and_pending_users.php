<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('location', 255)->nullable()->after('ruta');
        });

        Schema::table('pending_users', function (Blueprint $table) {
            $table->string('location', 255)->nullable()->after('ruta');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        Schema::table('pending_users', function (Blueprint $table) {
            $table->dropColumn('location');
        });
    }
};
