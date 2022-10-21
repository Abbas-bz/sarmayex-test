<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * Test task store
     *
     * @return void
     */
    public function test_task_creation_works_fine()
    {
        $user = User::factory()->create([
            'role' => 'member'
        ]);
        $this->actingAs($user);
        $response = $this->post('/api/tasks', [
            'title' => 'test',
            'description' => 'test'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);
    }

    /**
     * Test task update
     *
     * @return void
     */
    public function test_task_update()
    {
        $task = Task::factory()->create();
        $user = User::factory()
            ->hasAttached($task)
            ->create([
                'role' => 'member'
            ]);

        $this->actingAs($user);

        $response = $this->put('/api/tasks/' . $task->id, [
            'title' => 'test',
            'description' => 'test'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_task_delete()
    {
        $task = Task::factory()->create();
        $user = User::factory()
            ->hasAttached($task)
            ->create([
                'role' => 'member'
            ]);

        $this->actingAs($user);

        $response = $this->delete('/api/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);

        $response->assertStatus(200);
    }
}
