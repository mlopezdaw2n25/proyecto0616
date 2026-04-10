<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la taula password_resets per gestionar els tokens de recuperació.
     * No s'utilitza clau primària autoincremental: el email és l'identificador
     * únic perquè un usuari no pot tenir dos tokens actius simultàniament.
     */
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');          // Emmagatzemat amb bcrypt hash, mai en text pla
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
