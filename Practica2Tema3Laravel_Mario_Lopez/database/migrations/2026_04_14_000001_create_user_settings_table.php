<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Visibilitat / aparença
            $table->boolean('dark_mode')->default(false);
            $table->string('language', 10)->default('ca');   // 'ca' | 'es' | 'en'
            $table->string('font_size', 10)->default('medium'); // 'small' | 'medium' | 'large'
            $table->boolean('colorblind_mode')->default(false);

            // Privacitat
            $table->boolean('show_friends')->default(true);
            $table->boolean('show_likes')->default(true);
            $table->boolean('show_connections')->default(true);

            // Notificacions
            $table->boolean('notifications_enabled')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
