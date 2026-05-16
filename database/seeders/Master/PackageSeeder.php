<?php

namespace Database\Seeders\Master;

use App\Models\Master\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Paket Bulanan (Pay As You Go)
        Package::create([
            'name' => 'Pay As You Go',
            'code' => 'pay_as_you_go',
            'description' => 'Cocok untuk bisnis yang ingin fleksibilitas pengeluaran bulanan rendah.',
            'original_price' => null,
            'price' => 150000,
            'setup_fee' => 2500000,
            'features' => json_encode([
                'Setup Engine & Database',
                'Desain Tema Eksklusif',
                'Input Konten Awal oleh Tim',
                'Domain .com / .co.id (1 Thn)',
                'Hosting & SSL Terkelola',
                'Update Keamanan Berkala',
            ]),
            'billing_cycle' => 'monthly',
            'is_highlighted' => 0,
            'is_active' => 1,
            'version' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp,
            'deleted_at' => null,
        ]);

        // 2. Paket Tahunan (Annual Package)
        Package::create([
            'name' => 'Annual Package',
            'code' => 'annual_package',
            'description' => 'Pilihan terbaik untuk UMKM. Tanpa biaya jasa, fokus pada pertumbuhan.',
            'original_price' => null,
            'price' => 4200000,
            'setup_fee' => 0,
            'features' => json_encode([
                'GRATIS Biaya Setup (Save 2.5jt)',
                'Seluruh Fitur Paket Bulanan',
                'Prioritas Dukungan Teknis',
                'Penyimpanan Media Lebih Besar',
                'Optimasi SEO On-Page',
                'Laporan Performa Bulanan',
            ]),
            'billing_cycle' => 'annually',
            'is_highlighted' => 1,
            'is_active' => 1,
            'version' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp,
            'deleted_at' => null,
        ]);

        // 3. Paket Custom (Enterprise)
        Package::create([
            'name' => 'Custom Enterprise',
            'code' => 'custom_enterprise',
            'description' => 'Solusi sistem kompleks dengan fitur khusus sesuai kebutuhan spesifik skala besar.',
            'original_price' => null,
            'price' => 10000000,
            'setup_fee' => 0,
            'features' => json_encode([
                'Custom UI/UX & Animasi',
                'Integrasi API Pihak Ketiga',
                'Dedicated Server Terpisah',
                'SLA & Prioritas Support 24/7',
                'Pengembangan Fitur Khusus',
                'Training Penggunaan Sistem',
            ]),
            'billing_cycle' => 'custom',
            'is_highlighted' => 0,
            'is_active' => 1,
            'version' => 0,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp,
            'deleted_at' => null,
        ]);
    }
}