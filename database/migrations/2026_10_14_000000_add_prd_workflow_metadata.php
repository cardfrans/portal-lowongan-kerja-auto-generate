<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_vacancies', 'poster_template')) {
                $table->unsignedTinyInteger('poster_template')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'poster_generated_at')) {
                $table->timestamp('poster_generated_at')->nullable();
            }
            if (! Schema::hasIndex('job_vacancies', 'job_vacancies_company_status_index')) {
                $table->index(['company_id', 'status'], 'job_vacancies_company_status_index');
            }
        });

        Schema::table('job_edit_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_edit_logs', 'reason')) {
                $table->text('reason')->nullable();
            }
            if (! Schema::hasColumn('job_edit_logs', 'read_status')) {
                $table->enum('read_status', ['unread', 'read'])->nullable();
            }
            if (! Schema::hasIndex('job_edit_logs', 'job_edit_logs_vacancy_created_index')) {
                $table->index(['job_vacancy_id', 'created_at'], 'job_edit_logs_vacancy_created_index');
            }
            if (! Schema::hasIndex('job_edit_logs', 'job_edit_logs_read_created_index')) {
                $table->index(['read_status', 'created_at'], 'job_edit_logs_read_created_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table): void {
            $table->dropIndex('job_vacancies_company_status_index');
            $table->dropColumn(['poster_template', 'poster_generated_at']);
        });

        Schema::table('job_edit_logs', function (Blueprint $table): void {
            $table->dropIndex('job_edit_logs_vacancy_created_index');
            $table->dropIndex('job_edit_logs_read_created_index');
            $table->dropColumn(['reason', 'read_status']);
        });
    }
};
