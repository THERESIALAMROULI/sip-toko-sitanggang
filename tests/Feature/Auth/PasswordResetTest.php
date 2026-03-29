<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_route_is_disabled(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertNotFound();
    }

    public function test_reset_password_route_is_disabled(): void
    {
        $response = $this->get('/reset-password/sample-token');

        $response->assertNotFound();
    }
}
