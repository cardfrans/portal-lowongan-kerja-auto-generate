<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'business_area')) {
                $table->string('business_area')->nullable();
            }
            if (! Schema::hasColumn('users', 'province')) {
                $table->string('province')->nullable();
            }
            if (! Schema::hasColumn('users', 'company_website')) {
                $table->string('company_website')->nullable();
            }
            if (! Schema::hasColumn('users', 'company_email')) {
                $table->string('company_email')->nullable();
            }
            if (! Schema::hasColumn('users', 'contact_name')) {
                $table->string('contact_name')->nullable();
            }
            if (! Schema::hasColumn('users', 'contact_position')) {
                $table->string('contact_position')->nullable();
            }
            if (! Schema::hasColumn('users', 'contact_phone')) {
                $table->string('contact_phone')->nullable();
            }
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
