<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                ['name' => $category['name'], 'description' => $category['description']]
            );
        }
    }
}
