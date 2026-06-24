<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_category_index(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);
        Category::create([
            'name' => 'Do uong',
            'description' => 'Sample category',
        ]);

        $this->actingAs($user)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Do uong');
    }

    public function test_authenticated_admin_can_create_update_and_delete_category(): void
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user)
            ->post(route('admin.categories.store'), [
                'name' => 'Banh keo',
                'description' => 'Sweet snacks',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Banh keo')->firstOrFail();

        $this->actingAs($user)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Banh keo cap nhat',
                'description' => 'Updated description',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Banh keo cap nhat',
        ]);

        $this->actingAs($user)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_guest_cannot_access_category_management(): void
    {
        $this->get(route('admin.categories.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_category_management(): void
    {
        $user = User::factory()->create([
            'is_admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('admin.categories.index'))
            ->assertStatus(403);
    }
}
