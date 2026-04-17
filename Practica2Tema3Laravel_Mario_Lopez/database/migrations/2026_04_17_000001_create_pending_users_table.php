<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 100)->index();
            $table->string('password', 255);
            $table->string('ruta', 500);
            // Per als alumnes: referència al tipus; null per a empreses
            $table->unsignedBigInteger('tipus_user_id')->nullable();
            // 'empresa' o null (alumne)
            $table->string('tipus_type', 20)->nullable();
            // Token guardat com a hash SHA-256; el token pla només viatja per email
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_users');
    }
};
