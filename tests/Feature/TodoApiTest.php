<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_todos()
    {
        $user = User::factory()->create();
        Todo::factory(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/todos');

        $response->assertStatus(200)
                ->assertJsonCount(5, 'data')
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'details',
                            'status',
                            'user' => [
                                'id',
                                'name',
                                'email',
                            ],
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ],
                    ],
                ]);
    }

    /** @test */
    public function it_can_filter_todos_by_status()
    {
        $user = User::factory()->create();
        Todo::factory(3)->create(['status' => 'completed', 'user_id' => $user->id]);
        Todo::factory(2)->create(['status' => 'in progress', 'user_id' => $user->id]);

        $response = $this->getJson('/api/v1/todos?status=completed');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_search_todos_by_title_or_details()
    {
        $user = User::factory()->create();
        Todo::factory()->create(['title' => 'Test Laravel', 'user_id' => $user->id]);
        Todo::factory()->create(['details' => 'Write tests for Laravel', 'user_id' => $user->id]);

        $response = $this->getJson('/api/v1/todos?search=Laravel');

        $response->assertStatus(200)
                ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_can_sort_todos_by_created_at()
    {
        $user = User::factory()->create();
        Todo::factory()->create(['title' => 'First Todo', 'user_id' => $user->id, 'created_at' => now()->subDay()]);
        Todo::factory()->create(['title' => 'Latest Todo', 'user_id' => $user->id, 'created_at' => now()]);

        $response = $this->getJson('/api/v1/todos?sortBy=created_at&order=desc');

        $response->assertStatus(200)
                ->assertJsonPath('data.0.title', 'Latest Todo');
    }

    /** @test */
    public function it_can_create_a_todo()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'New Todo',
            'details' => 'Details about the new todo',
            'status' => 'not started',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/v1/todos', $data);

        $response->assertStatus(201)
                ->assertJsonPath('data.title', 'New Todo');
        $this->assertDatabaseHas('todos', $data);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_todo()
    {
        $response = $this->postJson('/api/v1/todos', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'status', 'user_id']);
    }

    /** @test */
    public function it_can_show_a_single_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/v1/todos/{$todo->id}");

        $response->assertStatus(200)
                ->assertJsonPath('data.id', $todo->id);
    }

    /** @test */
    public function it_can_update_a_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $data = [
            'title' => 'Updated Title',
            'status' => 'completed',
            'user_id' => $user->id,
        ];

        $response = $this->putJson("/api/v1/todos/{$todo->id}", $data);

        $response->assertStatus(200)
                ->assertJsonPath('data.title', 'Updated Title');
        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'title' => 'Updated Title']);
    }

    /** @test */
    public function it_can_delete_a_todo()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/v1/todos/{$todo->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('todos', ['id' => $todo->id]);
    }
}
