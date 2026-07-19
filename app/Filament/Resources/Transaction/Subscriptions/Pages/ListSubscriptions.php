<?php

namespace App\Filament\Resources\Transaction\Subscriptions\Pages;

use App\Filament\Resources\Transaction\Subscriptions\SubscriptionResource;
use App\Models\Master\Package;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Subscription;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->using(function (array $data, string $model): Subscription {
                    $date = now()->format('Ym');
                    $tenantCode = 'T' . str_pad($data['tenant_id'], 3, '0', STR_PAD_LEFT);
                    
                    $prefix = "SUB/{$tenantCode}/{$date}/";

                    $lastSub = $model::where('subscription_number', 'like', "{$prefix}%")
                        ->latest('id')
                        ->first();

                    if ($lastSub) {
                        $parts = explode('/', $lastSub->subscription_number);
                        $lastNumber = (int) end($parts);
                        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                    } else {
                        $newNumber = '0001';
                    }

                    $data['subscription_number'] = $prefix . $newNumber;

                    return $model::create($data);
                })
            ->after(function (Subscription $record) {
                    $package = Package::find($record->package_id);
                    $setupFee = $package ? $package->setup_fee : 0;
                    $totalAmountDue = $record->amount + $setupFee;

                    $dueDate = now()->addDays(7);
                    $dateString = $dueDate->format('Ymd');
                    
                    $tenantCode = 'T' . str_pad($record->tenant_id, 3, '0', STR_PAD_LEFT);
                    
                    $prefix = "INV/{$tenantCode}/{$dateString}/";

                    $lastInvoice = Payment::where('invoice_number', 'like', "{$prefix}%")
                        ->latest('id')
                        ->first();

                    if ($lastInvoice) {
                        $parts = explode('/', $lastInvoice->invoice_number);
                        $lastNumber = (int) end($parts);
                        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                    } else {
                        $newNumber = '0001';
                    }
                    
                    $invoiceNumber = $prefix . $newNumber;

                    $description = 'Tagihan Pertama: Langganan ' . $record->package_name;
                    if ($setupFee > 0) {
                        $description .= ' + Biaya Setup';
                    }

                    Payment::create([
                        'tenant_id'       => $record->tenant_id,
                        'subscription_id' => $record->id,
                        'invoice_number'  => $invoiceNumber,
                        'description'     => $description,
                        'amount_due'      => $totalAmountDue,
                        'due_date'        => $dueDate->format('Y-m-d'), 
                        'status'          => 'unpaid',
                    ]);
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin') => __('Beranda'),
            static::getResource()::getNavigationGroup(),
            static::getResource()::getBreadcrumb(),
        ];
    }
}
