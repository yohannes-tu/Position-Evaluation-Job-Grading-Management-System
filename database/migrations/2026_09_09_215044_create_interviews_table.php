<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();

            $table->dateTime('scheduled_at');

            $table->string('interview_type')->default('in_person');

            $table->string('location')->nullable();

            $table->string('meeting_link')->nullable();

            $table->integer('duration_minutes')
                ->default(60);

            $table->enum('status', [
                'scheduled',
                'confirmed',
                'completed',
                'cancelled',
                'rescheduled',
            ])->default('scheduled');

            $table->decimal('total_score', 8, 2)
                ->nullable();

            $table->enum('recommendation', [
                'recommended',
                'reserve',
                'not_recommended',
                'pending',
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index([
                'application_id',
                'status',
            ]);

            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};