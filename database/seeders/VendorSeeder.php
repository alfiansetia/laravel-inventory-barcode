<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('json/vendor.json');
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                // Validasi: skip kalau ada data yang null / kosong
                if (
                    empty($item['VEN_ID']) ||
                    empty($item['VEN_NAME']) ||
                    empty($item['NPWP']) ||
                    empty($item['VENDOR_TYPE'])
                ) {
                    continue; // atau bisa throw exception kalau mau benar2 strict
                }

                Vendor::create([
                    'vendor_id' => $item['VEN_ID'],
                    'name'      => $item['VEN_NAME'],
                    'npwp'      => $item['NPWP'],
                    'type'      => $item['VENDOR_TYPE'],
                ]);
            }
        });
    }
}
