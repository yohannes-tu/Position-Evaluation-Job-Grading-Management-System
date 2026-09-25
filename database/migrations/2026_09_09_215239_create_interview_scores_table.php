<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('interview_id')
                ->constrained('interviews')
                ->cascadeOnDelete();

            $table->foreignId('panel_member_id')
                ->constrained('interview_panel_members')
                ->cascadeOnDelete();

            $table->string('criterion');

            $table->decimal('score', 8, 2);

            $table->decimal('weight', 5, 2)
                ->default(0);

            $table->decimal('weighted_score', 8, 2)
                ->default(0);

            $table->text('comments')->nullable();

            $table->timestamps();

            $table->index([
                'interview_id',
                'panel_member_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_scores');
    }
};