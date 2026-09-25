<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->string('status')
                ->default('draft')
                ->after('is_active');

            $table->foreignId('approved_grade_id')
                ->nullable()
                ->after('status')
                ->constrained('grades')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->after('approved_grade_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');

            $table->text('approval_notes')
                ->nullable()
                ->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['approved_grade_id']);
            $table->dropForeign(['approved_by']);

            $table->dropColumn([
                'status',
                'approved_grade_id',
                'approved_by',
                'approved_at',
                'approval_notes',
            ]);
        });
    }
};