<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_account_and_logs_in(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'phone' => '0901234567',
            'address' => 'Ho Chi Minh City',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'a@example.com',
            'name' => 'Nguyen Van A',
            'phone' => '0901234567',
        ]);
    }

    public function test_login_authenticates_existing_user(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_is_rate_limited_after_multiple_failures(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_profile_and_password_update_work_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '0987654321',
                'address' => 'Da Nang',
            ])
            ->assertRedirect(route('profile'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '0987654321',
            'address' => 'Da Nang',
        ]);

        $this->actingAs($user->fresh())
            ->put(route('profile.password'), [
                'current_password' => 'password123',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ])
            ->assertRedirect();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }
}
