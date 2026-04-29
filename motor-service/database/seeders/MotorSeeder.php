<?php

namespace Database\Seeders;

use App\Models\Motor;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    /**
     * Seed data motor populer Indonesia
     */
    public function run(): void
    {
        $motors = [
            [
                'nama' => 'Honda Beat',
                'merk' => 'Honda',
                'tipe' => 'Matic',
                'tahun' => 2024,
                'harga' => 17500000,
                'stok' => 15,
                'warna' => 'Merah',
                'deskripsi' => 'Motor matic terlaris di Indonesia dengan desain stylish dan irit bahan bakar. Dilengkapi mesin 110cc eSP yang bertenaga dan efisien.',
                'gambar' => '/images/motors/honda_beat.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Honda Vario 160',
                'merk' => 'Honda',
                'tipe' => 'Matic',
                'tahun' => 2024,
                'harga' => 26500000,
                'stok' => 10,
                'warna' => 'Hitam',
                'deskripsi' => 'Skutik premium dengan mesin 160cc eSP+ bertenaga. Desain agresif dengan fitur canggih seperti Smart Key dan Honda Selectable Torque Control.',
                'gambar' => '/images/motors/honda_vario.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Yamaha NMAX 155',
                'merk' => 'Yamaha',
                'tipe' => 'Matic',
                'tahun' => 2024,
                'harga' => 30000000,
                'stok' => 8,
                'warna' => 'Biru',
                'deskripsi' => 'Motor matic premium kelas 155cc dengan teknologi VVA dan Traction Control System. Cocok untuk berkendara harian dan touring.',
                'gambar' => '/images/motors/yamaha_nmax.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Yamaha Aerox 155',
                'merk' => 'Yamaha',
                'tipe' => 'Matic',
                'tahun' => 2024,
                'harga' => 27000000,
                'stok' => 12,
                'warna' => 'Kuning',
                'deskripsi' => 'Skutik sporty dengan DNA racing. Dilengkapi mesin 155cc VVA, Y-Connect, dan desain aerodinamis yang agresif.',
                'gambar' => '/images/motors/yamaha_aerox.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Honda CBR 150R',
                'merk' => 'Honda',
                'tipe' => 'Sport',
                'tahun' => 2024,
                'harga' => 36000000,
                'stok' => 5,
                'warna' => 'Merah',
                'deskripsi' => 'Motor sport 150cc dengan performa tinggi. Full fairing dengan desain racing dan mesin DOHC 4 katup yang responsif.',
                'gambar' => '/images/motors/honda_cbr150r.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Yamaha R15 V4',
                'merk' => 'Yamaha',
                'tipe' => 'Sport',
                'tahun' => 2024,
                'harga' => 38000000,
                'stok' => 6,
                'warna' => 'Biru',
                'deskripsi' => 'Sportbike 155cc dengan teknologi VVA dan quickshifter. Desain terinspirasi dari YZF-R series dengan performa track-ready.',
                'gambar' => '/images/motors/yamaha_r15.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Suzuki Satria F150',
                'merk' => 'Suzuki',
                'tipe' => 'Bebek',
                'tahun' => 2024,
                'harga' => 24500000,
                'stok' => 7,
                'warna' => 'Hitam',
                'deskripsi' => 'Motor bebek sport legendaris dengan mesin 150cc DOHC. Akselerasi tercepat di kelasnya dengan bobot ringan.',
                'gambar' => '/images/motors/suzuki_satria.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Honda Supra X 125',
                'merk' => 'Honda',
                'tipe' => 'Bebek',
                'tahun' => 2024,
                'harga' => 19500000,
                'stok' => 9,
                'warna' => 'Merah',
                'deskripsi' => 'Motor bebek 125cc terpercaya dengan mesin irit dan tangguh. Pilihan tepat untuk penggunaan harian dengan daya tahan tinggi.',
                'gambar' => '/images/motors/honda_supra_x.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Kawasaki Ninja ZX-25R',
                'merk' => 'Kawasaki',
                'tipe' => 'Sport',
                'tahun' => 2024,
                'harga' => 96000000,
                'stok' => 3,
                'warna' => 'Hijau',
                'deskripsi' => 'Motor sport 250cc 4 silinder inline pertama di kelasnya. Mesin bertenaga besar dengan suara eksotis khas 4 silinder.',
                'gambar' => '/images/motors/kawasaki_zx25r.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Honda PCX 160',
                'merk' => 'Honda',
                'tipe' => 'Matic',
                'tahun' => 2024,
                'harga' => 33000000,
                'stok' => 7,
                'warna' => 'Putih',
                'deskripsi' => 'Skutik premium dengan mesin 160cc eSP+ dan Honda Selectable Torque Control. Desain mewah dengan fitur lengkap.',
                'gambar' => '/images/motors/honda_pcx.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Yamaha MX King 155',
                'merk' => 'Yamaha',
                'tipe' => 'Bebek',
                'tahun' => 2024,
                'harga' => 24000000,
                'stok' => 8,
                'warna' => 'Biru',
                'deskripsi' => 'Motor bebek sport 155cc VVA dengan performa tinggi. King of bebek sport dengan desain agresif dan handling lincah.',
                'gambar' => '/images/motors/yamaha_mx_king.png',
                'is_terlaris' => false,
            ],
            [
                'nama' => 'Honda CRF 150L',
                'merk' => 'Honda',
                'tipe' => 'Sport',
                'tahun' => 2024,
                'harga' => 35500000,
                'stok' => 4,
                'warna' => 'Merah',
                'deskripsi' => 'Motor trail sejati dengan mesin 150cc bertenaga. Suspensi panjang dan ground clearance tinggi untuk segala medan.',
                'gambar' => '/images/motors/honda_crf.png',
                'is_terlaris' => false,
            ],
        ];

        foreach ($motors as $motor) {
            Motor::create($motor);
        }
    }
}
