<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vacancy_id')
                ->constrained('vacancies')
                ->cascadeOnDelete();

            $table->foreignId('applicant_id')
                ->constrained('applicants')
                ->cascadeOnDelete();

            $table->string('application_number')
                ->unique();

            $table->dateTime('submitted_at')
                ->nullable();

            $table->string('status')
                ->default('submitted');

            $table->text('cover_letter')
                ->nullable();

            $table->decimal('screening_score', 8, 2)
                ->nullable();
            $table->enum('status', [
    'submitted',
    'under_review',
    'shortlisted',
    'interview',
    'selected',
    'hired',
    'rejected',
    'withdrawn',
]);

            $table->text('recruiter_notes')
                ->nullable();

            $table->timestamp('shortlisted_at')
                ->nullable();

            $table->timestamp('interview_at')
                ->nullable();

            $table->timestamp('selected_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->timestamps();

            $table->index([
                'vacancy_id',
                'status',
            ]);

            $table->index('applicant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};