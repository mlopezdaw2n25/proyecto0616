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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('extract',100);
            $table->string('body', 500);
            $table->string('url', 100);
            $table->enum('status',[1,2])->default(1);
            $table->foreignId('category_id')
                 ->constrained()
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            $table->foreignId('user_id')
                 ->constrained()
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
