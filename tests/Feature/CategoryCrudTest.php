<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_category_index(): void
    {
        $user = User::factory()->create();
        Category::create([
            'name' => 'Do uong',
            'description' => 'Sample category',
        ]);

        $this->actingAs($user)
            ->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Do uong');
    }

    public function test_authenticated_user_can_create_update_and_delete_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('categories.store'), [
                'name' => 'Banh keo',
                'description' => 'Sweet snacks',
            ])
            ->assertRedirect(route('categories.index'));

        $category = Category::where('name', 'Banh keo')->firstOrFail();

        $this->actingAs($user)
            ->put(route('categories.update', $category), [
                'name' => 'Banh keo cap nhat',
                'description' => 'Updated description',
            ])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Banh keo cap nhat',
        ]);

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_guest_cannot_access_category_management(): void
    {
        $this->get(route('categories.index'))->assertRedirect(route('login'));
    }
}
