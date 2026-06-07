<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observation_reports', function (Blueprint $table) {
            $table->id();

            // Reporter info
            $table->string('reporter_name');
            $table->string('reporter_role')->nullable();
            $table->string('reporter_programme')->nullable();

            // Lifecycle
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_reports');
    }
};
