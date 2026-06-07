<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot: each entry can have multiple types selected (observation, best_practice, etc.)
        Schema::create('observation_entry_types', function (Blueprint $table) {
            $table->unsignedBigInteger('observation_entry_id');

            // Maps to ENTRY_TYPES ids in observationData.js
            $table->enum('type', [
                'observation',
                'best_practice',
                'innovation',
                'challenge',
                'recommendation',
            ]);

            $table->primary(['observation_entry_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_entry_types');
    }
};
