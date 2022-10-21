<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use illuminate\Support\Str;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_users_list()
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_admin_users_gate()
    {
        $user = User::factory()->create([
            'role' => 'member'
        ]);

        $this->actingAs($user);

        $response = $this->get('/api/users');
        $response->assertStatus(403);
    }
}
