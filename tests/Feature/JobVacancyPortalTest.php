<?php

namespace Tests\Feature;

use App\Models\JobEditLog;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JobVacancyPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $companyA;
    protected User $companyB;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $companyRole = Role::firstOrCreate(['name' => 'company']);

        $this->admin = User::factory()->create([
            'name' => 'Admin UCC',
            'email' => 'admin@ucc.ac.id',
            'role' => 'admin',
        ]);
        $this->admin->assignRole($adminRole);

        $this->companyA = User::factory()->create([
            'name' => 'PT Astra International',
            'email' => 'astra@example.com',
            'role' => 'company',
        ]);
        $this->companyA->assignRole($companyRole);

        $this->companyB = User::factory()->create([
            'name' => 'PT Indofood CBP',
            'email' => 'indofood@example.com',
            'role' => 'company',
        ]);
        $this->companyB->assignRole($companyRole);
    }

    public function test_company_can_view_vacancies_index_and_create_page(): void
    {
        $response = $this->actingAs($this->companyA)->get(route('company.vacancies.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Lowongan Perusahaan');

        $createResponse = $this->actingAs($this->companyA)->get(route('company.vacancies.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Form Pengajuan Lowongan Kerja');
    }

    public function test_company_can_submit_new_job_vacancy(): void
    {
        $response = $this->actingAs($this->companyA)->post(route('company.vacancies.store'), [
            'company_name' => 'PT Astra International',
            'business_area' => 'Teknologi Informasi',
            'province' => 'Kepulauan Riau',
            'company_website' => 'https://astra.example.com',
            'contact_name' => 'Rina',
            'contact_position' => 'Recruiter',
            'contact_phone' => '08123456789',
            'company_email' => 'hr@astra.example.com',
            'position' => 'Data Analyst Specialist',
            'required_count' => 2,
            'qualifications' => "• S1 Statistika / Matematika / TI\n• Menguasai Python dan SQL",
            'job_description' => 'Menganalisis data operasional dan menyusun laporan berkala.',
            'other_info' => 'Penempatan Menara Astra Sudirman',
            'address' => 'Jl. Jend. Sudirman Kav. 5-6, Jakarta Pusat',
            'work_location' => 'Jakarta',
            'application_deadline' => now()->addDays(14)->toDateString(),
            'application_method' => 'Email',
            'application_address' => 'hr@astra.example.com',
            'information_consent' => '1',
            'publication_consent' => '1',
        ]);

        $this->assertDatabaseHas('job_vacancies', [
            'company_id' => $this->companyA->id,
            'position' => 'Data Analyst Specialist',
            'status' => 'pending',
        ]);

        $response->assertRedirect();
    }

    public function test_company_cannot_edit_another_company_vacancy(): void
    {
        $vacancyB = JobVacancy::create([
            'company_id' => $this->companyB->id,
            'company_name' => 'PT Indofood CBP',
            'position' => 'Brand Manager',
            'qualifications' => 'S1 Manajemen / Komunikasi',
            'address' => 'Jakarta Barat',
            'status' => 'pending',
        ]);

        // Company A attempts to view edit page of Company B's vacancy -> must be 403 Forbidden
        $response = $this->actingAs($this->companyA)->get(route('company.vacancies.edit', $vacancyB));
        $response->assertStatus(403);
    }

    public function test_company_can_update_vacancy_and_creates_edit_log(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->companyA->id,
            'company_name' => 'PT Astra International',
            'position' => 'Junior Programmer',
            'qualifications' => 'D3 Teknik Informatika',
            'address' => 'Jakarta Utara',
            'status' => 'rejected',
        ]);

        $response = $this->actingAs($this->companyA)->put(route('company.vacancies.update', $vacancy), [
            'company_name' => 'PT Astra International Tbk',
            'business_area' => 'Teknologi Informasi',
            'province' => 'Kepulauan Riau',
            'company_website' => 'https://astra.example.com',
            'contact_name' => 'Rina',
            'contact_position' => 'Recruiter',
            'contact_phone' => '08123456789',
            'company_email' => 'hr@astra.example.com',
            'position' => 'Junior Software Engineer',
            'required_count' => 1,
            'qualifications' => 'S1 Teknik Informatika / Sistem Informasi',
            'job_description' => 'Mengembangkan dan memelihara aplikasi internal perusahaan.',
            'other_info' => 'Gaji UMR + Insentif',
            'address' => 'Jl. Gaya Motor Raya No. 8, Sunter II, Jakarta Utara',
            'work_location' => 'Jakarta Utara',
            'application_deadline' => now()->addDays(14)->toDateString(),
            'application_method' => 'Email',
            'application_address' => 'hr@astra.example.com',
            'information_consent' => '1',
            'publication_consent' => '1',
            'edit_reason' => 'Mengubah titel posisi dan kualifikasi dari D3 ke S1.',
        ]);

        $response->assertRedirect();

        // Rejected submissions return as revised for admin review.
        $this->assertDatabaseHas('job_vacancies', [
            'id' => $vacancy->id,
            'position' => 'Junior Software Engineer',
            'status' => 'revised',
        ]);

        // Check edit log created
        $this->assertDatabaseHas('job_edit_logs', [
            'job_vacancy_id' => $vacancy->id,
            'edit_reason' => 'Mengubah titel posisi dan kualifikasi dari D3 ke S1.',
            'status' => 'unread',
        ]);
    }

    public function test_admin_can_approve_and_reject_vacancy(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->companyA->id,
            'company_name' => 'PT Astra International',
            'position' => 'Supply Chain Trainee',
            'qualifications' => 'S1 Teknik Industri',
            'address' => 'Jakarta',
            'status' => 'pending',
        ]);

        // 1. Admin Approves
        $approveResponse = $this->actingAs($this->admin)->post(route('admin.vacancies.approve', $vacancy));
        $approveResponse->assertRedirect();
        $this->assertEquals('approved', $vacancy->fresh()->status);

        // 2. Admin Rejects with reason
        $rejectResponse = $this->actingAs($this->admin)->post(route('admin.vacancies.reject', $vacancy), [
            'rejection_reason' => 'Mohon sertakan masa berlaku lowongan dan link email penerimaan berkas.',
        ]);
        $rejectResponse->assertRedirect();
        $this->assertEquals('rejected', $vacancy->fresh()->status);
        $this->assertEquals('Mohon sertakan masa berlaku lowongan dan link email penerimaan berkas.', $vacancy->fresh()->rejection_reason);
    }

    public function test_company_can_see_admin_rejection_reason_in_revision_list(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->companyA->id,
            'company_name' => 'PT Astra International',
            'position' => 'Supply Chain Trainee',
            'qualifications' => 'S1 Teknik Industri',
            'address' => 'Jakarta',
            'status' => 'pending',
        ]);

        $reason = 'Mohon lengkapi masa berlaku lowongan dan alamat email penerimaan berkas.';

        $this->actingAs($this->admin)->post(route('admin.vacancies.reject', $vacancy), [
            'rejection_reason' => $reason,
        ])->assertRedirect();

        $response = $this->actingAs($this->companyA)->get(route('company.vacancies.index', [
            'status' => 'rejected',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Perlu Revisi (Rejected)');
        $response->assertSee('Catatan Admin:');
        $response->assertSee($reason);
    }

    public function test_poster_render_routes_accessible_for_admin(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->companyA->id,
            'company_name' => 'PT Astra International',
            'position' => 'IT Auditor',
            'qualifications' => 'S1 Sistem Informasi',
            'address' => 'Jakarta',
            'status' => 'approved',
        ]);

        // Preview page
        $previewResponse = $this->actingAs($this->admin)->get(route('admin.posters.preview', $vacancy));
        $previewResponse->assertStatus(200);

        // Only the two PRD templates are available.
        foreach ([1, 2] as $tmpl) {
            $renderResponse = $this->actingAs($this->admin)->get(route('admin.posters.render', ['jobVacancy' => $vacancy, 'templateId' => $tmpl]));
            $renderResponse->assertStatus(200);
            $renderResponse->assertSee('IT Auditor');
        }

        $this->actingAs($this->admin)
            ->get(route('admin.posters.render', ['jobVacancy' => $vacancy, 'templateId' => 3]))
            ->assertNotFound();
    }
}
