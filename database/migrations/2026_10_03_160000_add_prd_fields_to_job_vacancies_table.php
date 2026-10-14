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
            foreach ([
                "ALTER TABLE job_vacancies ADD COLUMN business_area VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN work_location VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN province VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN company_website VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN contact_name VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN contact_position VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN contact_phone VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN company_email VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN required_count INT UNSIGNED NULL",
                "ALTER TABLE job_vacancies ADD COLUMN job_description TEXT NULL",
                "ALTER TABLE job_vacancies ADD COLUMN application_deadline DATE NULL",
                "ALTER TABLE job_vacancies ADD COLUMN application_method VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN application_address VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN offered_salary VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN supporting_image_path VARCHAR(255) NULL",
                "ALTER TABLE job_vacancies ADD COLUMN promotional_caption TEXT NULL",
                "ALTER TABLE job_vacancies ADD COLUMN information_consent TINYINT(1) NOT NULL DEFAULT 0",
                "ALTER TABLE job_vacancies ADD COLUMN publication_consent TINYINT(1) NOT NULL DEFAULT 0",
                "ALTER TABLE job_vacancies ADD COLUMN admin_reject_reason TEXT NULL",
                "ALTER TABLE job_vacancies ADD COLUMN company_revision_reason TEXT NULL",
            ] as $statement) {
                DB::statement($statement);
            }

            DB::statement("ALTER TABLE job_vacancies MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");

            return;
        }

        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('business_area')->nullable();
            $table->string('work_location')->nullable();
            $table->string('province')->nullable();
            $table->string('company_website')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_position')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('company_email')->nullable();
            $table->unsignedInteger('required_count')->nullable();
            $table->text('job_description')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('application_method')->nullable();
            $table->string('application_address')->nullable();
            $table->string('offered_salary')->nullable();
            $table->string('supporting_image_path')->nullable();
            $table->text('promotional_caption')->nullable();
            $table->boolean('information_consent')->default(false);
            $table->boolean('publication_consent')->default(false);
            $table->text('admin_reject_reason')->nullable();
            $table->text('company_revision_reason')->nullable();
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
