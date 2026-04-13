<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            // Drop the single-column unique on sender_id if it exists
            // (was created by mistake in the first migration)
            $indexes = collect(DB::select("SHOW INDEX FROM connections"))
                ->pluck('Key_name')->unique()->values();

            if ($indexes->contains('connections_sender_id_unique')) {
                $table->dropUnique('connections_sender_id_unique');
            }

            // Ensure the composite unique (sender_id, receiver_id) exists
            if (!$indexes->contains('connections_sender_id_receiver_id_unique')) {
                $table->unique(['sender_id', 'receiver_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropUnique(['sender_id', 'receiver_id']);
            $table->unique('sender_id');
        });
    }
};
