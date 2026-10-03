<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('business_area')->nullable()->after('company_name');
            $table->string('work_location')->nullable()->after('address');
            $table->string('province')->nullable()->after('address');
            $table->string('company_website')->nullable()->after('province');
            $table->string('contact_name')->nullable()->after('company_website');
            $table->string('contact_position')->nullable()->after('contact_name');
            $table->string('contact_phone')->nullable()->after('contact_position');
            $table->string('company_email')->nullable()->after('contact_phone');
            $table->unsignedInteger('required_count')->nullable()->after('position');
            $table->text('job_description')->nullable()->after('qualifications');
            $table->date('application_deadline')->nullable()->after('job_description');
            $table->string('application_method')->nullable()->after('application_deadline');
            $table->string('application_address')->nullable()->after('application_method');
            $table->string('offered_salary')->nullable()->after('application_address');
            $table->string('supporting_image_path')->nullable()->after('generated_poster_path');
            $table->text('promotional_caption')->nullable()->after('supporting_image_path');
            $table->boolean('information_consent')->default(false)->after('promotional_caption');
            $table->boolean('publication_consent')->default(false)->after('information_consent');
            $table->text('admin_reject_reason')->nullable()->after('rejection_reason');
            $table->text('company_revision_reason')->nullable()->after('admin_reject_reason');
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
