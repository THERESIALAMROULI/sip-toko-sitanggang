<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = Pengguna::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Informasi Profil')
            ->assertSee('Username')
            ->assertDontSee('Email')
            ->assertDontSee('Hapus Akun');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = Pengguna::factory()->create();
        $originalEmail = $user->email;

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'username' => 'testuser',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('testuser', $user->username);
        $this->assertSame($originalEmail, $user->email);
    }

    public function test_profile_update_keeps_existing_email(): void
    {
        $user = Pengguna::factory()->create();
        $originalEmail = $user->email;

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'username' => $user->username,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame($originalEmail, $user->refresh()->email);
    }

    public function test_profile_delete_route_is_not_available(): void
    {
        $user = Pengguna::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile');

        $response->assertMethodNotAllowed();
        $this->assertNotNull($user->fresh());
    }
}
