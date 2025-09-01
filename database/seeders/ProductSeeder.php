<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('json/product.json');
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                // Validasi: skip kalau ada data yang null / kosong
                if (
                    empty($item['MATL_CODE']) ||
                    empty($item['PART_NAME']) ||
                    empty($item['BUY_UM'])
                ) {
                    continue; // atau bisa throw exception kalau mau benar2 strict
                }

                Product::create([
                    'code' => $item['MATL_CODE'],
                    'name'  => $item['PART_NAME'],
                    'satuan'     => $item['BUY_UM'],
                ]);
            }
        });
    }
}
