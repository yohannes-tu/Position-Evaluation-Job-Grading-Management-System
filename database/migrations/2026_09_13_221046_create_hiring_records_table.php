<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiring_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->unique()
                ->constrained('applications')
                ->cascadeOnDelete();

            $table->foreignId('vacancy_id')
                ->constrained('vacancies')
                ->cascadeOnDelete();

            $table->foreignId('hired_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('hired_at');

            $table->string('employment_status')
                ->default('pending');

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_records');
    }
};