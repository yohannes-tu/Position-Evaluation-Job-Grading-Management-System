<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->string('name')->after('id');

            $table->decimal('min_score', 8, 2)
                ->default(0)
                ->after('name');

            $table->decimal('max_score', 8, 2)
                ->default(0)
                ->after('min_score');

            $table->boolean('is_active')
                ->default(true)
                ->after('max_score');
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'min_score',
                'max_score',
                'is_active',
            ]);
        });
    }
};