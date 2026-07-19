<x-filament-widgets::widget>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
        <x-filament::section class="fi-wi-stats-card" style="position: relative; overflow: hidden; padding-bottom: 0;">
            <div style="padding-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase;">Total Klien Aktif</span>
                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background-color: #d1fae5; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 1rem; height: 1rem; color: #059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div style="margin-top: 0.5rem;">
                    <span style="font-size: 2.25rem; font-weight: 800; color: #0f172a; line-height: 1;">{{ number_format($klienAktif, 0, ',', '.') }}</span>
                    <span style="font-size: 1rem; font-weight: 600; color: #64748b;"> / {{ number_format($totalKlien, 0, ',', '.') }}</span>
                </div>
                <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.25rem; background-color: #ecfdf5; padding: 0.25rem 0.5rem; border-radius: 9999px;">
                        <svg style="width: 0.75rem; height: 0.75rem; color: #059669;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span style="font-size: 0.625rem; font-weight: 700; color: #059669;">Meningkat 3 dari bulan lalu</span>
                    </div>
                </div>
            </div>
            <div style="position: absolute; bottom: 0; left: 1rem; width: 40%; height: 4px; background-color: #059669; border-radius: 2px 2px 0 0;"></div>
        </x-filament::section>
        <x-filament::section class="fi-wi-stats-card">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase;">Paket Langganan Aktif</span>
                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background-color: #e0f2fe; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 1rem; height: 1rem; color: #0284c7;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <div style="margin-top: 0.5rem; display: flex; align-items: baseline; gap: 0.5rem;">
                    <span style="font-size: 2.25rem; font-weight: 800; color: #0f172a; line-height: 1;">{{ number_format($totalLangganan, 0, ',', '.') }}</span>
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Total langganan berstatus aktif</span>
                </div>
                <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.25rem;">
                    <svg style="width: 0.75rem; height: 0.75rem; color: #94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span style="font-size: 0.625rem; color: #94a3b8; font-weight: 500;">Berdasarkan siklus tagihan saat ini</span>
                </div>
            </div>
        </x-filament::section>
        <x-filament::section class="fi-wi-stats-card">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.05em; text-transform: uppercase;">Total Pendapatan</span>
                    <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background-color: #fee2e2; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 1rem; height: 1rem; color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div style="margin-top: 0.5rem;">
                    <span style="font-size: 1.75rem; font-weight: 800; color: #0f172a; line-height: 1; letter-spacing: -0.025em;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
                <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 500;">Pendapatan bulan berjalan (MRR)</span>
                    <svg style="width: 0.875rem; height: 0.875rem; color: #059669;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                    <svg style="width: 0.75rem; height: 0.75rem; color: #94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span style="font-size: 0.625rem; color: #94a3b8; font-weight: 500;">Terakhir diperbarui 2 menit yang lalu</span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
