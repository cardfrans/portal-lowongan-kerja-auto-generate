<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table): void {
            $table->index(['company_id', 'status', 'created_at'], 'job_vacancies_company_status_created_index');
            $table->index(['status', 'created_at'], 'job_vacancies_status_created_index');
        });

        Schema::table('job_edit_logs', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'job_edit_logs_status_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table): void {
            $table->dropIndex('job_vacancies_company_status_created_index');
            $table->dropIndex('job_vacancies_status_created_index');
        });

        Schema::table('job_edit_logs', function (Blueprint $table): void {
            $table->dropIndex('job_edit_logs_status_created_index');
        });
    }
};
