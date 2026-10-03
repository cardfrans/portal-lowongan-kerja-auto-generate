<?php

namespace Database\Seeders;

use App\Models\JobEditLog;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Setup Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $companyRole = Role::firstOrCreate(['name' => 'company']);

        // 2. Setup Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@ucc.ac.id'],
            [
                'name' => 'UCC Career Center Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Setup Company 1
        $company1 = User::firstOrCreate(
            ['email' => 'company@pertamina.com'],
            [
                'name' => 'PT Pertamina (Persero)',
                'password' => Hash::make('password123'),
                'role' => 'company',
            ]
        );
        $company1->assignRole($companyRole);

        // 4. Setup Company 2
        $company2 = User::firstOrCreate(
            ['email' => 'company@telkom.co.id'],
            [
                'name' => 'PT Telkom Indonesia Tbk',
                'password' => Hash::make('password123'),
                'role' => 'company',
            ]
        );
        $company2->assignRole($companyRole);

        // 5. Seed Job Vacancies for Testing
        $job1 = JobVacancy::create([
            'company_id' => $company1->id,
            'company_name' => 'PT Pertamina (Persero)',
            'position' => 'Senior Mechanical Reliability Engineer',
            'qualifications' => "• S1 Teknik Mesin / Material / Metalurgi\n• Pengalaman minimal 3 tahun di industri Oil & Gas\n• Menguasai analisis vibrasi dan reliability equipment\n• Mampu berkomunikasi dalam Bahasa Inggris aktif",
            'other_info' => 'Penempatan: Refinery Unit IV Cilacap. Status: Kontrak 1 tahun dengan peluang permanen.',
            'address' => 'Jl. MT Haryono No. 77, Lomanis, Cilacap, Jawa Tengah',
            'status' => 'pending',
            'selected_template' => 1,
        ]);

        $job2 = JobVacancy::create([
            'company_id' => $company1->id,
            'company_name' => 'PT Pertamina (Persero)',
            'position' => 'HSE Officer (Health, Safety & Environment)',
            'qualifications' => "• D4/S1 K3 / Teknik Lingkungan\n• Memiliki sertifikasi AK3 Umum Kemnaker RI yang masih aktif\n• Paham regulasi SMK3 & ISO 45001\n• Pengalaman minimal 2 tahun di lapangan",
            'other_info' => 'Gaji kompetitif, tunjangan kesehatan keluarga, dan program pelatihan bersertifikat.',
            'address' => 'Graha Pertamina, Jl. Medan Merdeka Timur No. 11-13, Gambir, Jakarta Pusat',
            'status' => 'approved',
            'selected_template' => 2,
        ]);

        $job3 = JobVacancy::create([
            'company_id' => $company2->id,
            'company_name' => 'PT Telkom Indonesia Tbk',
            'position' => 'Fullstack Web Engineer',
            'qualifications' => "• S1 Teknik Informatika / Sistem Informasi / Ilmu Komputer\n• Mahir PHP (Laravel), JavaScript (Vue/React), dan Tailwind CSS\n• Terbiasa mengelola MySQL, Redis, dan RESTful API\n• Memiliki portofolio aplikasi web live",
            'other_info' => 'Skema kerja Hybrid (3 hari WFO, 2 hari WFH). BPJS Ketenagakerjaan & Asuransi Swasta.',
            'address' => 'Telkom Landmark Tower, Jl. Jend. Gatot Subroto Kav. 52, Kuningan Barat, Jakarta Selatan',
            'status' => 'pending',
            'selected_template' => 3,
        ]);

        $job4 = JobVacancy::create([
            'company_id' => $company2->id,
            'company_name' => 'PT Telkom Indonesia Tbk',
            'position' => 'Junior UI/UX Designer',
            'qualifications' => "• D3/S1 Desain Komunikasi Visual atau bidang terkait\n• Portofolio studi kasus desain produk digital di Figma\n• Memahami prinsip design system dan micro-interactions",
            'other_info' => 'Kandidat fresh graduate dipersilakan mendaftar.',
            'address' => 'Telkom Corporate University, Jl. Gegerkalong Hilir No. 47, Sukasari, Bandung',
            'status' => 'rejected',
            'rejection_reason' => 'Mohon lengkapi kriteria pengalaman kerja dan cantumkan tautan form pendaftaran/email lamaran resmi di bagian Info Tambahan.',
        ]);

        // Seed an edit log for job1
        JobEditLog::create([
            'job_vacancy_id' => $job1->id,
            'edit_reason' => 'Memperbarui kriteria sertifikasi keandalan mesin sesuai standar RU IV.',
            'old_data' => [
                'position' => 'Mechanical Reliability Engineer',
                'qualifications' => 'S1 Teknik Mesin, pengalaman 2 tahun.',
            ],
            'status' => 'unread',
        ]);
    }
}
