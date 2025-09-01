<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('json/karyawan.json');
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                // Validasi: skip kalau ada data yang null / kosong
                if (
                    empty($item['NO KARTU ABSEN']) ||
                    empty($item['NOMOR ID CARD']) ||
                    empty($item['NAMA KARYAWAN'])
                ) {
                    continue; // atau bisa throw exception kalau mau benar2 strict
                }

                Karyawan::create([
                    'id_absen' => $item['NO KARTU ABSEN'],
                    'id_card'  => $item['NOMOR ID CARD'],
                    'name'     => $item['NAMA KARYAWAN'],
                ]);
            }
        });
    }
}
