<?php

namespace Database\Seeders\Master;

use App\Models\Master\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            // ==========================================
            // COMPANY PROFILE
            // ==========================================
            // 1. Landing Page (Basic)
            [
                'name'           => 'Landing Page',
                'code'           => 'cp_basic_monthly',
                'website_type'   => 'company_profile',
                'tier'           => 'basic',
                'description'    => 'Solusi cepat dan hemat untuk peluncuran kampanye atau produk tunggal.',
                'original_price' => 350000,
                'price'          => 299000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 7,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Pilihan Template Basic', 'is_highlighted' => false],
                    ['text' => 'Hingga 5 Halaman Utama', 'is_highlighted' => false],
                    ['text' => 'Hosting & SSL Terkelola', 'is_highlighted' => false],
                    ['text' => 'Integrasi Kontak WhatsApp', 'is_highlighted' => true],
                    ['text' => 'Standar SEO & Analytics', 'is_highlighted' => false],
                    ['text' => 'Gratis Setup & Subdomain', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Landing Page',
                'code'           => 'cp_basic_annually',
                'website_type'   => 'company_profile',
                'tier'           => 'basic',
                'description'    => 'Solusi cepat dan hemat untuk peluncuran kampanye atau produk tunggal.',
                'original_price' => 4200000,
                'price'          => 2990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 7,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Pilihan Template Basic', 'is_highlighted' => false],
                    ['text' => 'Hingga 5 Halaman Utama', 'is_highlighted' => false],
                    ['text' => 'Hosting & SSL Terkelola', 'is_highlighted' => false],
                    ['text' => 'Integrasi Kontak WhatsApp', 'is_highlighted' => true],
                    ['text' => 'Standar SEO & Analytics', 'is_highlighted' => false],
                    ['text' => 'Gratis Setup & Subdomain', 'is_highlighted' => false],
                ],
            ],

            // 2. Corporate Profile (Premium)
            [
                'name'           => 'Corporate Profile',
                'code'           => 'cp_premium_monthly',
                'website_type'   => 'company_profile',
                'tier'           => 'premium',
                'description'    => 'Website korporat komprehensif untuk mendongkrak kredibilitas bisnis.',
                'original_price' => 750000,
                'price'          => 599000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 14,
                'is_highlighted' => true,
                'features'       => [
                    ['text' => 'Semua Fitur Landing Page', 'is_highlighted' => true],
                    ['text' => 'Akses Premium Templates', 'is_highlighted' => false],
                    ['text' => 'Hingga 10 Halaman Utama', 'is_highlighted' => false],
                    ['text' => 'Custom Domain (.com/.id)', 'is_highlighted' => false],
                    ['text' => 'Manajemen Inbox & Artikel', 'is_highlighted' => false],
                    ['text' => 'Advanced SEO & Analytics', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Corporate Profile',
                'code'           => 'cp_premium_annually',
                'website_type'   => 'company_profile',
                'tier'           => 'premium',
                'description'    => 'Website korporat komprehensif untuk mendongkrak kredibilitas bisnis.',
                'original_price' => 9000000,
                'price'          => 5990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 14,
                'is_highlighted' => true,
                'features'       => [
                    ['text' => 'Semua Fitur Landing Page', 'is_highlighted' => true],
                    ['text' => 'Akses Premium Templates', 'is_highlighted' => false],
                    ['text' => 'Hingga 10 Halaman Utama', 'is_highlighted' => false],
                    ['text' => 'Custom Domain (.com/.id)', 'is_highlighted' => false],
                    ['text' => 'Manajemen Inbox & Artikel', 'is_highlighted' => false],
                    ['text' => 'Advanced SEO & Analytics', 'is_highlighted' => false],
                ],
            ],

            // 3. Custom Portal (Custom)
            [
                'name'           => 'Custom Portal',
                'code'           => 'cp_custom_monthly',
                'website_type'   => 'company_profile',
                'tier'           => 'custom',
                'description'    => 'Sistem informasi internal/eksternal khusus dengan logika operasional unik.',
                'original_price' => 1750000,
                'price'          => 1499000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 0,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Semua Fitur Corporate Profile', 'is_highlighted' => true],
                    ['text' => 'Aplikasi Single Page (SPA)', 'is_highlighted' => false],
                    ['text' => 'Custom Role & Permission', 'is_highlighted' => false],
                    ['text' => 'Integrasi API Eksternal', 'is_highlighted' => false],
                    ['text' => 'Dukungan Multi-bahasa', 'is_highlighted' => false],
                    ['text' => 'Infrastruktur Cloud & Skalabilitas', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Custom Portal',
                'code'           => 'cp_custom_annually',
                'website_type'   => 'company_profile',
                'tier'           => 'custom',
                'description'    => 'Sistem informasi internal/eksternal khusus dengan logika operasional unik.',
                'original_price' => 21000000,
                'price'          => 14990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 0,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Semua Fitur Corporate Profile', 'is_highlighted' => true],
                    ['text' => 'Aplikasi Single Page (SPA)', 'is_highlighted' => false],
                    ['text' => 'Custom Role & Permission', 'is_highlighted' => false],
                    ['text' => 'Integrasi API Eksternal', 'is_highlighted' => false],
                    ['text' => 'Dukungan Multi-bahasa', 'is_highlighted' => false],
                    ['text' => 'Infrastruktur Cloud & Skalabilitas', 'is_highlighted' => false],
                ],
            ],


            // ==========================================
            // COMMERCE
            // ==========================================
            // 1. Commerce Starter (Basic)
            [
                'name'           => 'Commerce Starter',
                'code'           => 'ce_basic_monthly',
                'website_type'   => 'commerce',
                'tier'           => 'basic',
                'description'    => 'Validasi pasar digital dengan etalase kustom yang ringkas dan elegan.',
                'original_price' => 450000,
                'price'          => 399000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 7,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Pilihan Template E-Commerce', 'is_highlighted' => false],
                    ['text' => 'Katalog Hingga 50 Produk', 'is_highlighted' => false],
                    ['text' => 'Checkout via WhatsApp', 'is_highlighted' => true],
                    ['text' => 'Manajemen Kampanye & Voucher', 'is_highlighted' => false],
                    ['text' => 'Standar SEO & Analytics', 'is_highlighted' => false],
                    ['text' => 'Gratis Setup & Subdomain', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Commerce Starter',
                'code'           => 'ce_basic_annually',
                'website_type'   => 'commerce',
                'tier'           => 'basic',
                'description'    => 'Validasi pasar digital dengan etalase kustom yang ringkas dan elegan.',
                'original_price' => 5400000,
                'price'          => 3990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 7,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Pilihan Template E-Commerce', 'is_highlighted' => false],
                    ['text' => 'Katalog Hingga 50 Produk', 'is_highlighted' => false],
                    ['text' => 'Checkout via WhatsApp', 'is_highlighted' => true],
                    ['text' => 'Manajemen Kampanye & Voucher', 'is_highlighted' => false],
                    ['text' => 'Standar SEO & Analytics', 'is_highlighted' => false],
                    ['text' => 'Gratis Setup & Subdomain', 'is_highlighted' => false],
                ],
            ],

            // 2. Integrated Commerce (Premium)
            [
                'name'           => 'Integrated Commerce',
                'code'           => 'ce_premium_monthly',
                'website_type'   => 'commerce',
                'tier'           => 'premium',
                'description'    => 'Otomatisasi bisnis dengan sistem 24/7 memproses pembayaran & logistik.',
                'original_price' => 950000,
                'price'          => 799000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 14,
                'is_highlighted' => true,
                'features'       => [
                    ['text' => 'Semua Fitur Commerce Starter', 'is_highlighted' => true],
                    ['text' => 'Produk Tidak Terbatas', 'is_highlighted' => false],
                    ['text' => 'Payment Gateway Otomatis', 'is_highlighted' => false],
                    ['text' => 'Otomasi Cek Ongkir (Logistik)', 'is_highlighted' => false],
                    ['text' => 'Dasbor Pelanggan & Laporan Realtime', 'is_highlighted' => false],
                    ['text' => 'Custom Domain (.com/.id)', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Integrated Commerce',
                'code'           => 'ce_premium_annually',
                'website_type'   => 'commerce',
                'tier'           => 'premium',
                'description'    => 'Otomatisasi bisnis dengan sistem 24/7 memproses pembayaran & logistik.',
                'original_price' => 11400000,
                'price'          => 7990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 14,
                'is_highlighted' => true,
                'features'       => [
                    ['text' => 'Semua Fitur Commerce Starter', 'is_highlighted' => true],
                    ['text' => 'Produk Tidak Terbatas', 'is_highlighted' => false],
                    ['text' => 'Payment Gateway Otomatis', 'is_highlighted' => false],
                    ['text' => 'Otomasi Cek Ongkir (Logistik)', 'is_highlighted' => false],
                    ['text' => 'Dasbor Pelanggan & Laporan Realtime', 'is_highlighted' => false],
                    ['text' => 'Custom Domain (.com/.id)', 'is_highlighted' => false],
                ],
            ],

            // 3. Enterprise Scale (Custom)
            [
                'name'           => 'Enterprise Scale',
                'code'           => 'ce_custom_monthly',
                'website_type'   => 'commerce',
                'tier'           => 'custom',
                'description'    => 'Ekosistem skala besar untuk logika B2B, multi-cabang, atau marketplace.',
                'original_price' => 2500000,
                'price'          => 1999000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'monthly',
                'trial_days'     => 0,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Semua Fitur Integrated Commerce', 'is_highlighted' => true],
                    ['text' => 'Arsitektur Multi-Tenant / Marketplace', 'is_highlighted' => false],
                    ['text' => 'Logika Komisi Vendor & B2B', 'is_highlighted' => false],
                    ['text' => 'Real-time WebSockets', 'is_highlighted' => false],
                    ['text' => 'Server Load Balancing & Docker', 'is_highlighted' => false],
                    ['text' => 'Integrasi Sistem & AI Analytics', 'is_highlighted' => false],
                ],
            ],
            [
                'name'           => 'Enterprise Scale',
                'code'           => 'ce_custom_annually',
                'website_type'   => 'commerce',
                'tier'           => 'custom',
                'description'    => 'Ekosistem skala besar untuk logika B2B, multi-cabang, atau marketplace.',
                'original_price' => 30000000,
                'price'          => 19990000,
                'setup_fee'      => 0,
                'billing_cycle'  => 'annually',
                'trial_days'     => 0,
                'is_highlighted' => false,
                'features'       => [
                    ['text' => 'Semua Fitur Integrated Commerce', 'is_highlighted' => true],
                    ['text' => 'Arsitektur Multi-Tenant / Marketplace', 'is_highlighted' => false],
                    ['text' => 'Logika Komisi Vendor & B2B', 'is_highlighted' => false],
                    ['text' => 'Real-time WebSockets', 'is_highlighted' => false],
                    ['text' => 'Server Load Balancing & Docker', 'is_highlighted' => false],
                    ['text' => 'Integrasi Sistem & AI Analytics', 'is_highlighted' => false],
                ],
            ],
        ];

        DB::beginTransaction();
        try {
            DB::statement('TRUNCATE TABLE mst_packages RESTART IDENTITY CASCADE');

            foreach ($packages as $pkg) {
                Package::create([
                    'name'           => $pkg['name'],
                    'code'           => $pkg['code'],
                    'website_type'   => $pkg['website_type'],
                    'tier'           => $pkg['tier'],
                    'description'    => $pkg['description'],
                    'original_price' => $pkg['original_price'],
                    'price'          => $pkg['price'],
                    'setup_fee'      => $pkg['setup_fee'],
                    'features'       => $pkg['features'],
                    'billing_cycle'  => $pkg['billing_cycle'],
                    'trial_days'     => $pkg['trial_days'],
                    'is_highlighted' => $pkg['is_highlighted'] ? 1 : 0,
                    'is_active'      => 1,
                    'version'        => 0,
                    'created_by'     => 1,
                    'updated_by'     => 1,
                    'deleted_by'     => null,
                    'created_at'     => now()->timestamp,
                    'updated_at'     => now()->timestamp,
                    'deleted_at'     => null,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
