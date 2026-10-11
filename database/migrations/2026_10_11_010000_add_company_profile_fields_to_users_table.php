<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('business_area')->nullable()->after('logo_path');
            $table->string('province')->nullable()->after('business_area');
            $table->string('company_website')->nullable()->after('province');
            $table->string('company_email')->nullable()->after('company_website');
            $table->string('contact_name')->nullable()->after('company_email');
            $table->string('contact_position')->nullable()->after('contact_name');
            $table->string('contact_phone')->nullable()->after('contact_position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'business_area',
                'province',
                'company_website',
                'company_email',
                'contact_name',
                'contact_position',
                'contact_phone',
            ]);
        });
    }
};
