<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->foreignId('interview_criterion_id')
                ->nullable()
                ->after('panel_member_id')
                ->constrained('interview_criteria')
                ->nullOnDelete();

            $table->index('interview_criterion_id');
        });
    }

    public function down(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->dropForeign([
                'interview_criterion_id',
            ]);

            $table->dropIndex([
                'interview_criterion_id',
            ]);

            $table->dropColumn(
                'interview_criterion_id'
            );
        });
    }
};