<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Log Aktivitas Sistem Terbaru
        </x-slot>

        <div class="overflow-x-auto -mx-4 -mb-4 sm:-mx-6 sm:-mb-6">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50/50 dark:bg-white/5">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white sm:px-6">Status</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white sm:px-6">Aktivitas</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white sm:px-6">Deskripsi</th>
                        <th scope="col" class="px-4 py-3 font-medium text-gray-900 dark:text-white sm:px-6 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 sm:px-6">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-500/20 text-green-600 flex items-center justify-center">
                                <x-heroicon-o-plus-circle class="w-4 h-4" />
                            </div>
                        </td>
                        <td class="px-4 py-3 sm:px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Tenant Baru Dibuat
                        </td>
                        <td class="px-4 py-3 sm:px-6">
                            Dimas menambahkan klien "GOR Jaya Abadi" dengan paket Tahunan.
                        </td>
                        <td class="px-4 py-3 sm:px-6 text-right whitespace-nowrap text-xs">
                            10 Menit lalu
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 sm:px-6">
                            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 flex items-center justify-center">
                                <x-heroicon-o-no-symbol class="w-4 h-4" />
                            </div>
                        </td>
                        <td class="px-4 py-3 sm:px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Penangguhan Tenant
                        </td>
                        <td class="px-4 py-3 sm:px-6">
                            Sistem otomatis menangguhkan "Klinik Sehat" karena keterlambatan bayar.
                        </td>
                        <td class="px-4 py-3 sm:px-6 text-right whitespace-nowrap text-xs">
                            1 Jam lalu
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 sm:px-6">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 flex items-center justify-center">
                                <x-heroicon-o-paint-brush class="w-4 h-4" />
                            </div>
                        </td>
                        <td class="px-4 py-3 sm:px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            Pembaruan Tema Klien
                        </td>
                        <td class="px-4 py-3 sm:px-6">
                            Pranesha mengubah theme_code untuk "Hotel Maju" menjadi 'dark_corporate'.
                        </td>
                        <td class="px-4 py-3 sm:px-6 text-right whitespace-nowrap text-xs">
                            Kemarin
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>