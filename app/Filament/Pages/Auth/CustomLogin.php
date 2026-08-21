<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.base';
    }

    public function getView(): string
    {
        return 'filament.pages.auth.custom-login';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Masuk ke TerasSapa';
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->validationMessages([
                'required' => 'Email tidak boleh kosong.',
                'email' => 'Format email tidak valid.',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->validationMessages([
                'required' => 'Password tidak boleh kosong.',
            ]);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }
}
