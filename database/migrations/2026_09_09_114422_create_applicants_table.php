<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();

            $table->string('application_account_id')
                ->nullable()
                ->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            $table->string('email')->index();
            $table->string('phone', 30);

            $table->date('date_of_birth')->nullable();

            $table->string('gender')->nullable();

            $table->string('nationality')
                ->default('Ethiopian');

            $table->string('national_id')
                ->nullable();

            $table->text('address')->nullable();

            $table->string('city')->nullable();
            $table->string('region')->nullable();

            $table->timestamps();

            $table->index([
                'first_name',
                'last_name',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};