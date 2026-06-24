<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Nước giải khát',
                'description' => 'Các loại nước ngọt có ga, nước tinh khiết và nước giải khát.',
            ],
            [
                'id' => 2,
                'name' => 'Bánh kẹo',
                'description' => 'Các loại bánh ngọt, bánh quy, kẹo và đồ ăn vặt.',
            ],
            [
                'id' => 3,
                'name' => 'Thực phẩm khô / Gia vị',
                'description' => 'Mì gói, đồ khô và các gia vị nấu ăn.',
            ],
            [
                'id' => 4,
                'name' => 'Đồ ăn liền',
                'description' => 'Các loại mì ăn liền, phở và đồ hộp tiện lợi.',
            ],
            [
                'id' => 5,
                'name' => 'Sữa / Sản phẩm từ sữa',
                'description' => 'Sữa tươi, sữa chua, sữa hạt và các sản phẩm từ sữa.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['id' => $category['id']],
                ['name' => $category['name'], 'description' => $category['description']]
            );
        }
    }
}
