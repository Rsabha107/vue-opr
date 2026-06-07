<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observation_report_venues', function (Blueprint $table) {
            $table->id();

            // Parent report — FK to be added in relations migration
            $table->unsignedBigInteger('observation_report_id');

            // Optional link to the existing venues table — FK to be added in relations migration
            $table->unsignedBigInteger('venue_id')->nullable();

            // Free-text venue type chosen from the dropdown
            $table->string('venue_type')->nullable();
            // Only populated when venue_type = 'Other (please specify)'
            $table->string('venue_type_other')->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_report_venues');
    }
};
