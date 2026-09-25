<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_criteria', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->text('description')->nullable();

            $table->decimal('weight', 5, 2);

            $table->decimal('max_score', 8, 2)
                ->default(100);

            $table->boolean('is_active')
                ->default(true);

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'is_active',
                'sort_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_criteria');
    }
};