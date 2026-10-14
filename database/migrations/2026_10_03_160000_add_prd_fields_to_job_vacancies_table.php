<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb', 'tidb'], true)) {
            $columns = [
                'business_area' => 'VARCHAR(255) NULL',
                'work_location' => 'VARCHAR(255) NULL',
                'province' => 'VARCHAR(255) NULL',
                'company_website' => 'VARCHAR(255) NULL',
                'contact_name' => 'VARCHAR(255) NULL',
                'contact_position' => 'VARCHAR(255) NULL',
                'contact_phone' => 'VARCHAR(255) NULL',
                'company_email' => 'VARCHAR(255) NULL',
                'required_count' => 'INT UNSIGNED NULL',
                'job_description' => 'TEXT NULL',
                'application_deadline' => 'DATE NULL',
                'application_method' => 'VARCHAR(255) NULL',
                'application_address' => 'VARCHAR(255) NULL',
                'offered_salary' => 'VARCHAR(255) NULL',
                'supporting_image_path' => 'VARCHAR(255) NULL',
                'promotional_caption' => 'TEXT NULL',
                'information_consent' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'publication_consent' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'admin_reject_reason' => 'TEXT NULL',
                'company_revision_reason' => 'TEXT NULL',
            ];

            foreach ($columns as $name => $definition) {
                if (! Schema::hasColumn('job_vacancies', $name)) {
                    DB::statement("ALTER TABLE job_vacancies ADD COLUMN {$name} {$definition}");
                }
            }

            DB::statement("ALTER TABLE job_vacancies MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");

            return;
        }

        Schema::table('job_vacancies', function (Blueprint $table) {
            if (! Schema::hasColumn('job_vacancies', 'business_area')) {
                $table->string('business_area')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'work_location')) {
                $table->string('work_location')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'province')) {
                $table->string('province')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'company_website')) {
                $table->string('company_website')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'contact_name')) {
                $table->string('contact_name')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'contact_position')) {
                $table->string('contact_position')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'contact_phone')) {
                $table->string('contact_phone')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'company_email')) {
                $table->string('company_email')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'required_count')) {
                $table->unsignedInteger('required_count')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'job_description')) {
                $table->text('job_description')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'application_deadline')) {
                $table->date('application_deadline')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'application_method')) {
                $table->string('application_method')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'application_address')) {
                $table->string('application_address')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'offered_salary')) {
                $table->string('offered_salary')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'supporting_image_path')) {
                $table->string('supporting_image_path')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'promotional_caption')) {
                $table->text('promotional_caption')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'information_consent')) {
                $table->boolean('information_consent')->default(false);
            }
            if (! Schema::hasColumn('job_vacancies', 'publication_consent')) {
                $table->boolean('publication_consent')->default(false);
            }
            if (! Schema::hasColumn('job_vacancies', 'admin_reject_reason')) {
                $table->text('admin_reject_reason')->nullable();
            }
            if (! Schema::hasColumn('job_vacancies', 'company_revision_reason')) {
                $table->text('company_revision_reason')->nullable();
            }
        });

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'revised'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->change();
            $table->dropColumn([
                'business_area',
                'work_location',
                'province',
                'company_website',
                'contact_name',
                'contact_position',
                'contact_phone',
                'company_email',
                'required_count',
                'job_description',
                'application_deadline',
                'application_method',
                'application_address',
                'offered_salary',
                'supporting_image_path',
                'promotional_caption',
                'information_consent',
                'publication_consent',
                'admin_reject_reason',
                'company_revision_reason',
            ]);
        });
    }
};
