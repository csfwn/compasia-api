<?php

namespace Database\Seeders;

use App\Models\ProductMasterList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductMasterListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProductMasterList::truncate();
        Schema::enableForeignKeyConstraints();

        $items = [
            [
                'product_id' => 4450,
                'type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone SE',
                'capacity' => '2GB/16GB',
                'quantity' => 13,
            ],
            [
                'product_id' => 4768,
                'type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone SE',
                'capacity' => '2GB/32GB',
                'quantity' => 30,
            ],
            [
                'product_id' => 4451,
                'type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone SE',
                'capacity' => '2GB/64GB',
                'quantity' => 20,
            ],
            [
                'product_id' => 4574,
                'type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone SE',
                'capacity' => '2GB/128GB',
                'quantity' => 16,
            ],
            [
                'product_id' => 6039,
                'type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone SE (2020)',
                'capacity' => '3GB/64GB',
                'quantity' => 18,
            ],
        ];

        foreach ($items as $data) {
            ProductMasterList::create($data);
        }
    }
}