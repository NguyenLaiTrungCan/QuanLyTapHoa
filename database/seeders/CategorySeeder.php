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
                'name' => 'Đồ uống / Giải khát',
                'description' => 'Các loại nước ngọt có ga, nước tinh khiết, trà, cà phê.',
            ],
            [
                'id' => 2,
                'name' => 'Bánh kẹo / Ăn vặt',
                'description' => 'Các loại bánh ngọt, bánh quy, kẹo, và đồ ăn vặt các loại.',
            ],
            [
                'id' => 3,
                'name' => 'Thực phẩm khô / Gia vị',
                'description' => 'Các loại gia vị nấu ăn, đồ khô dự trữ.', // Thêm vào để lấp khoảng trống ID 3
            ],
            [
                'id' => 4,
                'name' => 'Đồ ăn liền',
                'description' => 'Các loại mì tôm, phở, miến gói, đồ hộp tiện lợi.',
            ],
            [
                'id' => 5,
                'name' => 'Sữa / Sản phẩm từ sữa',
                'description' => 'Sữa tươi, sữa chua, sữa hạt và các chế phẩm khác từ sữa.',
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
