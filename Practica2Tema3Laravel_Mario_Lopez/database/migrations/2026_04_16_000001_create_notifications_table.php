<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Who receives the notification
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Who triggered the action
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            // 'like', 'comment', 'connection'
            $table->string('type');
            // Post linked to this notification (null for connection-type)
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
            // Read / unread
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
