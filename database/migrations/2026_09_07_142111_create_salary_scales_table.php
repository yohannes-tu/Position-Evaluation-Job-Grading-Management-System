<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_scales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grade_id')
                ->constrained('grades')
                ->cascadeOnDelete();

            $table->decimal('minimum_salary', 12, 2);

            $table->decimal('midpoint_salary', 12, 2);

            $table->decimal('maximum_salary', 12, 2);

            $table->string('currency', 10)
                ->default('ETB');

            $table->date('effective_date');

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'grade_id',
                'effective_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_scales');
    }
};