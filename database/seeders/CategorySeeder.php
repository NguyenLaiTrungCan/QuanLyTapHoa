<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Đồ uống', 'description' => 'Nước ngọt, nước suối, trà, cà phê và các loại đồ uống khác.'],
            ['name' => 'Bánh kẹo', 'description' => 'Các loại bánh, kẹo và snack.'],
            ['name' => 'Gia vị', 'description' => 'Nước mắm, muối, đường, tiêu và gia vị nấu ăn.'],
            ['name' => 'Mì gói', 'description' => 'Mì ăn liền, phở ăn liền, cháo ăn liền.'],
            ['name' => 'Sữa & thực phẩm khô', 'description' => 'Sữa, ngũ cốc, đồ khô và thực phẩm đóng gói.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
