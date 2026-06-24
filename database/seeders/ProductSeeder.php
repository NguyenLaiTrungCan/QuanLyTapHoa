<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $products = [
            [
                'name' => 'Mì Hảo Hảo Tôm Chua Cay',
                'price' => 4500,
                'description' => 'Mì ăn liền vị tôm chua cay.',
                'category_id' => 3,
                'image' => 'products/haohao.jpg',
            ],
            [
                'name' => 'Coca Cola 330ml',
                'price' => 10000,
                'description' => 'Nước giải khát có ga.',
                'category_id' => 1,
                'image' => 'products/coca.jpg',
            ],
            [
                'name' => 'Pepsi 330ml',
                'price' => 10000,
                'description' => 'Nước giải khát Pepsi.',
                'category_id' => 1,
                'image' => 'products/pepsi.jpg',
            ],
            [
                'name' => 'Sữa Vinamilk 180ml',
                'price' => 8000,
                'description' => 'Sữa tiệt trùng có đường.',
                'category_id' => 5,
                'image' => 'products/vinamilk.jpg',
            ],
            [
                'name' => 'Bánh Oreo Chocolate',
                'price' => 15000,
                'description' => 'Bánh quy nhân kem chocolate.',
                'category_id' => 2,
                'image' => 'products/oreo.jpg',
            ],
            [
                'name' => 'Nước Suối Aquafina 500ml',
                'price' => 7000,
                'description' => 'Nước uống tinh khiết.',
                'category_id' => 1,
                'image' => 'products/aquafina.jpg',
            ],
            [
                'name' => 'Kẹo Alpenliebe',
                'price' => 12000,
                'description' => 'Kẹo sữa caramel.',
                'category_id' => 2,
                'image' => 'products/alpenliebe.jpg',
            ],
            [
                'name' => 'Bánh Chocopie',
                'price' => 35000,
                'description' => 'Bánh phủ socola.',
                'category_id' => 2,
                'image' => 'products/chocopie.jpg',
            ],
            [
                'name' => 'Trà Xanh Không Độ',
                'price' => 12000,
                'description' => 'Trà xanh đóng chai.',
                'category_id' => 1,
                'image' => 'products/khongdo.jpg',
            ],
            [
                'name' => 'Cà Phê G7 3in1',
                'price' => 45000,
                'description' => 'Cà phê hòa tan G7.',
                'category_id' => 1,
                'image' => 'products/g7.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
