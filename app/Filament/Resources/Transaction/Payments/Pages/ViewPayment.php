<?php

namespace App\Filament\Resources\Transaction\Payments\Pages;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            url("/admin") => __("Beranda"),
            static::getResource()::getNavigationGroup(),
            static::getResource()::getUrl("index") => static::getResource()::getBreadcrumb(),
            "Lihat",
        ];
    }
}
