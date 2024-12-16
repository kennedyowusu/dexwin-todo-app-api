<?php

namespace Tests\Unit;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_filter_todos_by_status()
    {
        $user = User::factory()->create();

        Todo::factory()->create(['status' => 'completed', 'user_id' => $user->id]);
        Todo::factory()->create(['status' => 'in progress', 'user_id' => $user->id]);

        $completedTodos = Todo::filterByStatus('completed')->get();

        $this->assertCount(1, $completedTodos);
        $this->assertEquals('completed', $completedTodos->first()->status);
    }

    /** @test */
    public function it_can_search_todos_by_title_or_details()
    {
        $user = User::factory()->create();

        Todo::factory()->create(['title' => 'Laravel Testing', 'user_id' => $user->id]);
        Todo::factory()->create(['details' => 'Write unit tests', 'user_id' => $user->id]);

        $searchResults = Todo::search('Testing')->get();

        $this->assertCount(1, $searchResults);
        $this->assertEquals('Laravel Testing', $searchResults->first()->title);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $todo->user);
        $this->assertEquals($user->id, $todo->user->id);
    }
}
