<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();

            /*
             * Approved position being recruited for
             */
            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            /*
             * Vacancy identification
             */
            $table->string('vacancy_code', 50)
                ->unique();

            /*
             * Number of available positions
             */
            $table->unsignedInteger('number_of_openings')
                ->default(1);

            /*
             * Recruitment dates
             */
            $table->date('posting_date')
                ->nullable();

            $table->date('closing_date')
                ->nullable();

            /*
             * Employment information
             */
            $table->string('employment_type', 100)
                ->nullable();

            $table->string('location', 255)
                ->nullable();

            /*
             * Salary visibility
             *
             * Actual salary information should not
             * automatically become public.
             */
            $table->boolean('show_salary')
                ->default(false);

            /*
             * Vacancy content
             */
            $table->text('description')
                ->nullable();

            $table->text('application_instructions')
                ->nullable();

            /*
             * Required documents can be stored
             * as JSON so administrators can configure them.
             */
            $table->json('required_documents')
                ->nullable();

            /*
             * Workflow
             */
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'published',
                'closed',
                'cancelled',
            ])->default('draft');

            /*
             * Approval information
             */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();

            /*
             * Publication information
             */
            $table->timestamp('published_at')
                ->nullable();

            /*
             * Closing information
             */
            $table->timestamp('closed_at')
                ->nullable();

            $table->timestamps();

            /*
             * Indexes
             */
            $table->index('position_id');
            $table->index('status');
            $table->index('posting_date');
            $table->index('closing_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};