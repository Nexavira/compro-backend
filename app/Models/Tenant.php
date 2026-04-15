<?php

namespace App\Models;

use Sushi\Sushi;

class Tenant extends BaseModel
{
    use Sushi;

    protected array $rows = [
        [
            'id' => 1,
            'uuid' => '550e8400-e29b-41d4-a716-446655440001',
            'name' => 'GOR Jaya Abadi',
            'slug' => 'gor-jaya',
            'custom_domain' => 'gorjaya.com',
            'theme_code' => 'light_neon',
            'is_suspended' => false,
            // Kolom Wajib dari BaseModel
            'is_active' => true,
            'version' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => null,
            'created_at' => 1713150000,
            'updated_at' => 1713150000,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'uuid' => '550e8400-e29b-41d4-a716-446655440002',
            'name' => 'Klinik Sehat Bersama',
            'slug' => 'klinik-sehat',
            'custom_domain' => null,
            'theme_code' => 'clean_medical',
            'is_suspended' => true,
            'is_active' => true,
            'version' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => null,
            'created_at' => 1713250000,
            'updated_at' => 1713250000,
            'deleted_at' => null,
        ],
        [
            'id' => 3,
            'uuid' => '550e8400-e29b-41d4-a716-446655440003',
            'name' => 'PT Manufaktur Maju',
            'slug' => 'manufaktur-maju',
            'custom_domain' => 'majumanufaktur.co.id',
            'theme_code' => 'dark_corporate',
            'is_suspended' => false,
            'is_active' => true,
            'version' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => null,
            'created_at' => 1713350000,
            'updated_at' => 1713350000,
            'deleted_at' => null,
        ],
    ];

    public function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_suspended' => 'boolean',
            'is_active' => 'boolean',
        ]);
    }
}