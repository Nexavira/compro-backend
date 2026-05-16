<?php

namespace App\Filament\Resources\Transaction\Payments\Pages;

use App\Filament\Resources\Transaction\Payments\PaymentResource;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
