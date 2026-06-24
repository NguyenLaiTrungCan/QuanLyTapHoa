<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventories = [
            ['name' => 'Mì Hảo Hảo Tôm Chua Cay',   'quantity' => 200, 'location' => 'Kệ A1'],
            ['name' => 'Coca Cola 330ml',              'quantity' => 150, 'location' => 'Kệ B1'],
            ['name' => 'Pepsi 330ml',                  'quantity' => 150, 'location' => 'Kệ B2'],
            ['name' => 'Sữa Vinamilk 180ml',           'quantity' => 120, 'location' => 'Kệ C1'],
            ['name' => 'Bánh Oreo Chocolate',          'quantity' =>  80, 'location' => 'Kệ D1'],
            ['name' => 'Nước Suối Aquafina 500ml',     'quantity' => 200, 'location' => 'Kệ B3'],
            ['name' => 'Kẹo Alpenliebe',               'quantity' => 300, 'location' => 'Kệ D2'],
            ['name' => 'Bánh Chocopie',                'quantity' =>  60, 'location' => 'Kệ D3'],
            ['name' => 'Trà Xanh Không Độ',            'quantity' => 100, 'location' => 'Kệ B4'],
            ['name' => 'Cà Phê G7 3in1',               'quantity' =>  50, 'location' => 'Kệ E1'],
        ];

        foreach ($inventories as $item) {
            $product = Product::where('name', $item['name'])->first();

            if ($product) {
                Inventory::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'quantity' => $item['quantity'],
                        'location' => $item['location'],
                    ]
                );
            }
        }
    }
}
