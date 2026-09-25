<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('evaluation_factor_id')
                ->constrained('evaluation_factors')
                ->cascadeOnDelete();

            $table->string('name');

            $table->text('description')->nullable();

            $table->decimal('points', 8, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_criteria');
    }
};