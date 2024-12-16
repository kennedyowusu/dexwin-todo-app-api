<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_todos()
    {
        $user = User::factory()->create();
        Todo::factory(5)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/todos');

        $response->assertStatus(200)
                ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_create_a_todo()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'New Todo',
            'details' => 'This is a new todo',
            'status' => 'in progress',
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

    /** @test */
    public function it_returns_the_correct_response_structure()
    {
        $user = User::factory()->create();
        Todo::factory(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/todos');

        $response->assertStatus(200)
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
}
