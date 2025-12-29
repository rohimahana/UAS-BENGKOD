<?php

namespace Tests\Feature;

use App\Models\User;
// Use the framework CSRF middleware class when disabling it in tests
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page can be displayed.
     */
    public function test_login_page_can_be_displayed(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test register page can be displayed.
     */
    public function test_register_page_can_be_displayed(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test user can register successfully.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'nama' => 'Test User',
            'alamat' => 'Test Address',
            'no_ktp' => '1234567890123456',
            'no_hp' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // disable only CSRF middleware for this test POST so session remains available
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register', $userData);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'pasien',
        ]);
    }

    /**
     * Test user can login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // disable only CSRF middleware for login POST so session remains available
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/login', [
                'email' => 'test@example.com',
                'password' => 'password123',
            ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // disable only CSRF middleware for login POST so session remains available
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test authenticated user can logout.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        // disable only CSRF middleware for logout POST so session remains available
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
