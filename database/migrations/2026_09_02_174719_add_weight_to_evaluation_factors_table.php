<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('evaluation_factors', 'weight')) {
            Schema::table('evaluation_factors', function (Blueprint $table) {
                $table->decimal('weight', 5, 2)
                    ->default(0)
                    ->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('evaluation_factors', 'weight')) {
            Schema::table('evaluation_factors', function (Blueprint $table) {
                $table->dropColumn('weight');
            });
        }
    }
};