<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE applications
            MODIFY status ENUM(
                'submitted',
                'under_review',
                'shortlisted',
                'interview',
                'selected',
                'hired',
                'rejected',
                'withdrawn'
            ) NOT NULL DEFAULT 'submitted'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE applications
            MODIFY status ENUM(
                'submitted',
                'under_review',
                'shortlisted',
                'interview',
                'selected',
                'rejected',
                'withdrawn'
            ) NOT NULL DEFAULT 'submitted'
        ");
    }
};