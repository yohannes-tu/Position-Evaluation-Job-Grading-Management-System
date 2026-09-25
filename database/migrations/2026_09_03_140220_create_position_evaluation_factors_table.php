<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_evaluation_factors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('position_evaluation_id')
                ->constrained('position_evaluations')
                ->cascadeOnDelete();

            $table->foreignId('evaluation_factor_id')
                ->constrained('evaluation_factors')
                ->cascadeOnDelete();

            $table->foreignId('evaluation_criterion_id')
                ->nullable()
                ->constrained('evaluation_criteria')
                ->nullOnDelete();

            $table->decimal('score', 8, 2)
                ->default(0);

            $table->decimal('weight', 5, 2)
                ->default(0);

            $table->decimal('weighted_score', 8, 2)
                ->default(0);

            $table->timestamps();

            $table->unique(
                ['position_evaluation_id', 'evaluation_factor_id'],
                'pef_evaluation_factor_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_evaluation_factors');
    }
};