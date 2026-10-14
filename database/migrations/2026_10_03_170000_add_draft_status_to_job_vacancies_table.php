<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb', 'tidb'], true)) {
            DB::statement("ALTER TABLE job_vacancies MODIFY status ENUM('draft', 'pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb', 'tidb'], true)) {
            DB::statement("ALTER TABLE job_vacancies MODIFY status ENUM('pending', 'approved', 'rejected', 'revised') NOT NULL DEFAULT 'pending'");
        }
    }
};
