<?php

namespace App\Filament\Resources\Tenant\Tenants\Schemas;

use App\Models\GlobalTemplate;
use App\Models\Master\Package;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        $operation = $schema->getOperation();

        return match ($operation) {
            'create' => self::getCreateSchema($schema),
            'edit'   => self::getEditSchema($schema),
            'view'   => self::getViewSchema($schema),
            default  => $schema,
        };
    }

    protected static function getCreateSchema(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                Step::make('1. Data Perusahaan')
                    ->description('Informasi profil Perusahaan/Klien')
                    ->schema(self::getTenantComponents()),
                Step::make('2. Akun Akses')
                    ->description('Email yang akan menerima tautan pengaturan kata sandi')
                    ->schema([
                        Section::make('Kredensial Admin Tenant')->schema([
                            TextInput::make('user_name')
                                ->label('Nama Pengelola')
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Nama Pengelola wajib diisi']),
                            TextInput::make('user_phone')
                                ->label('Nomor Telepon')
                                ->tel()
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Nomor Telepon wajib diisi']),
                            TextInput::make('user_email')
                                ->label('Email Perusahaan/Pengelola')
                                ->email()
                                ->unique(table: 'auth_users', column: 'email')
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Email Perusahaan/Pengelola wajib diisi']),
                        ])->columns(3),
                    ]),

                Step::make('3. Langganan')
                    ->description('Pilih paket untuk langganan Klien')
                    ->schema([
                        Section::make('Setup Langganan')->schema([
                            Select::make('subscription_plan')
                                ->label('Pilih Paket Langganan')
                                ->options(function () {
                                    return Package::where('is_active', 1)
                                        ->get()
                                        ->mapWithKeys(function ($package) {
                                            $priceFormatted = 'Rp ' . number_format($package->price, 0, ',', '.');
                                            $billing = $package->billing_cycle === 'monthly' ? '/bln' : ($package->billing_cycle === 'annually' ? '/thn' : '');
                                            return [$package->id => "{$package->name} - {$priceFormatted}{$billing}"];
                                        })
                                        ->toArray();
                                })
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->extraAttributes([
                                    'style' => 'cursor: pointer !important;',
                                ])
                                ->markAsRequired()
                                ->rules(['required'])
                                ->validationMessages(['required' => 'Paket Langganan wajib diisi']),
                        ]),
                    ]),
            ])
                ->columnSpanFull()
                ->skippable(false)
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button type="submit" size="sm">
                        Buat Klien
                    </x-filament::button>
                BLADE))),
        ]);
    }

    protected static function getEditSchema(Schema $schema): Schema
    {
        return $schema->components(self::getTenantComponents());
    }

    protected static function getViewSchema(Schema $schema): Schema
    {
        return $schema->components(self::getTenantComponents());
    }

    protected static function getTenantComponents(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Group::make()->schema([
                        Section::make('Informasi Klien')
                            ->description('Nama klien akan digunakan untuk membuat URL Prefix (Slug) secara otomatis.')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Klien')
                                    ->markAsRequired()
                                    ->rules(['required'])
                                    ->validationMessages(['required' => 'Nama Klien wajib diisi'])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('code')
                                    ->label('Kode Tenant')
                                    ->markAsRequired()
                                    ->rules(['required'])
                                    ->validationMessages(['required' => 'Kode Tenant wajib diisi'])
                                    ->unique(table: 'tnt_tenants', ignoreRecord: true),

                                TextInput::make('slug')
                                    ->label('URL Prefix')
                                    ->markAsRequired()
                                    ->rules(['required'])
                                    ->validationMessages(['required' => 'Slug / URL Prefix wajib diisi'])
                                    ->unique(table: 'tnt_tenants', ignoreRecord: true),

                                \Filament\Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),

                                Select::make('tenant_category_id')
                                    ->label('Kategori Klien')
                                    ->relationship('tenantCategory', 'name')
                                    ->native(false)
                                    ->searchable()
                                    ->preload()
                                    ->extraAttributes([
                                        'style' => 'cursor: pointer !important;',
                                    ])
                                    ->columnSpanFull()
                                    ->live(),
                            ]),
                        Section::make('Konfigurasi Teknis')
                            ->description('Atur tema antarmuka dan domain kustom untuk klien ini.')
                            ->columns(2)
                            ->schema([
                                Select::make('global_template_id')
                                    ->label('Template Global (Tema)')
                                    ->options(function (callable $get) {
                                        $categoryId = $get('tenant_category_id');
                                        if (!$categoryId) {
                                            return [];
                                        }
                                        return GlobalTemplate::where('tenant_category_id', $categoryId)
                                            ->where('is_active', 1)
                                            ->pluck('title', 'id');
                                    })
                                    ->native(false)
                                    ->searchable()
                                    ->preload()
                                    ->extraAttributes([
                                        'style' => 'cursor: pointer !important;',
                                    ])
                                    ->markAsRequired()
                                    ->rules(['required'])
                                    ->validationMessages(['required' => 'Global Template wajib diisi']),

                                TextInput::make('custom_domain')
                                    ->label('Domain Kustom')
                                    ->placeholder('client.com')
                                    ->prefix('https://'),
                            ]),
                    ])->columnSpan(['lg' => 2]),
                    Group::make()->schema([
                        Section::make('Status')
                            ->schema([
                                Toggle::make('is_suspended')
                                    ->label('Tangguhkan Klien?')
                                    ->helperText(new HtmlString("
                                        Jika ditangguhkan (merah), klien tidak akan bisa mengakses sistem.<br>
                                        <span style='color:red'>Merah = Ditangguhkan</span> . <span style='color:green'>Hijau = Aktif</span>
                                    "))
                                    ->onColor('danger')
                                    ->offColor('success')
                                    ->default(true)
                                    ->hidden(fn (string $operation): bool => $operation === 'create'),
                            ]),

                        Section::make('Branding')
                            ->description('Unggah logo dan favicon perusahaan.')
                            ->schema([
                                FileUpload::make('logo_upload')
                                    ->label('Logo Perusahaan')
                                    ->image()
                                    ->disk('public')
                                    ->directory('tenants/logos')
                                    ->formatStateUsing(fn($record) => $record?->logo?->file_path),

                                FileUpload::make('favicon_upload')
                                    ->label('Favicon')
                                    ->image()
                                    ->disk('public')
                                    ->directory('tenants/favicons')
                                    ->formatStateUsing(fn($record) => $record?->favicon?->file_path),
                            ]),
                    ])->columnSpan(['lg' => 1]),

                ])
                ->columnSpanFull(),
        ];
    }
}
