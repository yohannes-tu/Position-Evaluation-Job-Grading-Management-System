<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('position_evaluations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->cascadeOnDelete();

            $table->decimal('total_score', 8, 2)
                ->default(0);

            $table->enum('status', [
                'draft',
                'completed'
            ])->default('draft');

            $table->foreignId('evaluated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('evaluated_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('position_evaluations');
    }
};