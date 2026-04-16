<?php

namespace App\Filament\Resources\Auth\Roles\Schemas;

use App\Models\Auth\Permission;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PermissionRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $permissions = Permission::where('is_active', 1)
            ->orderBy('prefix_label')
            ->orderBy('module_label')
            ->orderBy('action_label')
            ->get();

        $groupedData = $permissions->groupBy(['guard_name', 'prefix_label', 'module_label']);

        $desiredOrder = ['admin', 'web', 'mobile'];
        $tabs = [];

        foreach ($desiredOrder as $guardName) {
            if (!isset($groupedData[$guardName])) continue;
            
            $groups = $groupedData[$guardName];
            $groupComponents = [];

            foreach ($groups as $groupLabel => $modules) {
                $moduleComponents = [];

                foreach ($modules as $moduleLabel => $actions) {
                    $moduleComponents[] = Section::make($moduleLabel)
                        ->compact()
                        ->collapsible()
                        ->collapsed()
                        ->aside(false)
                        ->schema([
                            CheckboxList::make('permissions')
                                ->label('')
                                ->options($actions->pluck('action_label', 'id'))
                                ->columns(2)
                                ->bulkToggleable()
                                ->formatStateUsing(function ($state, $livewire) {
                                    return $livewire->data['permissions'] ?? [];
                                })
                                ->dehydrated(false)
                        ])
                        ->extraAttributes([
                            'class' => 'border border-gray-200 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5'
                        ]);
                }

                $groupComponents[] = Section::make($groupLabel)
                    ->collapsible()
                    ->collapsed()
                    ->compact()
                    ->schema([
                        Grid::make(3) 
                            ->schema($moduleComponents)
                            ->gap(4)
                    ]);
            }

            $tabs[] = Tab::make(strtoupper($guardName))
                ->icon($guardName === 'web' ? 'heroicon-m-computer-desktop' : ($guardName === 'mobile' ? 'heroicon-m-device-phone-mobile' : 'heroicon-m-shield-check'))
                ->schema([
                    Grid::make(1)->schema($groupComponents)
                ]);
        }

        return $schema->components([
            Tabs::make('GuardPermissions')
                ->tabs($tabs)
                ->persistTabInQueryString()
                ->columnSpanFull(),
        ]);
    }
}