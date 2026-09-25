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
        Schema::create('positions', function (Blueprint $table) {

            $table->id();

            // Department relationship
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Basic position information
            $table->string('title');

            $table->string('code', 50)->unique();

            $table->text('description')->nullable();

            $table->text('responsibilities')->nullable();

            // Job requirements
            $table->string('education')->nullable();

            $table->string('experience')->nullable();

            // Employment information
            $table->enum('employment_type', [
                'full_time',
                'part_time',
                'contract'
            ])->default('full_time');

            // Position status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};