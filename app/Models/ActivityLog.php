<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ActivityLog extends Model
{
    use Sushi;

    protected array $rows = [
        [
            'id' => 1,
            'status' => 'success',
            'aktivitas' => 'Tenant Baru Dibuat',
            'deskripsi' => 'Dimas menambahkan klien "GOR Jaya Abadi" dengan paket Tahunan.',
            'waktu' => '10 Menit lalu',
        ],
        [
            'id' => 2,
            'status' => 'danger',
            'aktivitas' => 'Penangguhan Tenant',
            'deskripsi' => 'Sistem otomatis menangguhkan "Klinik Sehat" karena keterlambatan bayar.',
            'waktu' => '1 Jam lalu',
        ],
        [
            'id' => 3,
            'status' => 'info',
            'aktivitas' => 'Pembaruan Tema Klien',
            'deskripsi' => 'Pranesha mengubah theme_code untuk "Hotel Maju" menjadi dark_corporate.',
            'waktu' => 'Kemarin',
        ],
    ];
}