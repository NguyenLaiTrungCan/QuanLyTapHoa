<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_list_and_show_categories(): void
    {
        $category = Category::create([
            'name' => 'Do uong',
            'description' => 'Sample category',
        ]);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Do uong',
            ]);

        $this->getJson('/api/categories/'.$category->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'Do uong');
    }

    public function test_authenticated_user_can_create_update_and_delete_category(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/categories', [
                'name' => 'Banh keo',
                'description' => 'Sweet snacks',
            ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Banh keo');

        $category = Category::where('name', 'Banh keo')->firstOrFail();

        $this->actingAs($user)
            ->putJson('/api/categories/'.$category->id, [
                'name' => 'Banh keo cap nhat',
                'description' => 'Updated description',
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Banh keo cap nhat');

        $this->actingAs($user)
            ->deleteJson('/api/categories/'.$category->id)
            ->assertOk()
            ->assertJsonPath('message', 'Đã xóa danh mục thành công.');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_category_api_prevents_deletion_when_category_has_products(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Gia vi',
            'description' => null,
        ]);

        DB::table('products')->insert([
            'name' => 'Nuoc mam',
            'price' => 10000,
            'description' => 'Sample product',
            'category_id' => $category->id,
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->deleteJson('/api/categories/'.$category->id)
            ->assertStatus(422)
            ->assertJsonPath('message', 'Không thể xóa danh mục đang có sản phẩm.');
    }
}
