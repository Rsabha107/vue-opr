<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observation_entries', function (Blueprint $table) {
            $table->id();

            // Parent venue row — FK to be added in relations migration
            $table->unsignedBigInteger('observation_report_venue_id');

            // Core content
            $table->text('description');

            // Only populated when the "Other" functional area is ticked
            $table->string('area_other')->nullable();

            // Single-select: maps to APPLICABILITY ids in observationData.js
            $table->enum('applicability', [
                'must_have',
                'good_to_have',
                'already',
                'na',
                'assess',
            ])->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_entries');
    }
};
