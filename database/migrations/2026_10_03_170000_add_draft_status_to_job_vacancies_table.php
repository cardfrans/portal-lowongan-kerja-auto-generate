<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE job_vacancies MODIFY status ENUM('draft', 'pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE job_vacancies MODIFY status ENUM('pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");
        }
    }
};
