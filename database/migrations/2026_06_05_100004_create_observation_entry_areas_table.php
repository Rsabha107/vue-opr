<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot: each entry can have multiple functional areas ticked
        Schema::create('observation_entry_areas', function (Blueprint $table) {
            $table->unsignedBigInteger('observation_entry_id');

            // Short code from FUNCTIONAL_AREAS in observationData.js (e.g. "SAF", "TIC")
            $table->string('area_code', 20);

            $table->primary(['observation_entry_id', 'area_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_entry_areas');
    }
};
