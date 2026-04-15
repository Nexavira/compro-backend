<x-filament-widgets::widget>
    <x-filament::section class="border-0 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 bg-white dark:bg-gray-900 rounded-xl overflow-hidden">
        
        <div class="flex flex-row items-center justify-between p-2 sm:p-4 w-full">
            
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 sm:h-12 sm:w-12 shrink-0 flex items-center justify-center rounded-full bg-teal-100 text-teal-600">
                    <x-heroicon-s-sparkles class="h-5 w-5 sm:h-6 sm:w-6" width="24" height="24" />
                </div>
                <div>
                    <h2 class="text-lg sm:text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Selamat datang, <span class="text-teal-600">{{ auth()->user()->name ?? 'Admin' }}</span>!
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500">Ringkasan performa sistem hari ini.</p>
                </div>
            </div>

            <div class="flex items-center">
                <div class="inline-flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-inset ring-gray-200">
                    <x-heroicon-o-calendar-days class="h-5 w-5 text-gray-400" width="20" height="20" />
                    <span class="text-sm font-medium text-gray-700 whitespace-nowrap">
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
            
        </div>
    </x-filament::section>
</x-filament-widgets::widget>