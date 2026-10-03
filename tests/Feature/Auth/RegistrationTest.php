<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $image = imagecreatetruecolor(2, 2);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 255, 255, 255, 127));
        ob_start();
        imagepng($image);
        $logoContents = ob_get_clean();
        imagedestroy($image);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'logo' => UploadedFile::fake()->createWithContent('logo.png', $logoContents),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_company_can_register_without_a_logo(): void
    {
        $response = $this->post('/register', [
            'name' => 'Company Without Logo',
            'email' => 'without-logo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'without-logo@example.com',
            'logo_path' => null,
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
