<x-filament-widgets::widget>
    <x-filament::section class="fi-wi-latest-activity" style="padding: 0;">
        <div style="padding: 1.5rem 1.5rem 0 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b;">Log Aktivitas Sistem Terbaru</h3>
                <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Catatan perubahan dan aksi administratif terbaru</p>
            </div>
            <div style="display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem; background-color: #f8fafc; min-width: 250px;">
                <svg style="width: 1rem; height: 1rem; color: #94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Cari log..." style="border: none; background: transparent; outline: none; margin-left: 0.5rem; font-size: 0.875rem; width: 100%; color: #334155;">
            </div>
        </div>
        <div style="margin-top: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                        <th style="padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; width: 80px;">Status</th>
                        <th style="padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Aktivitas</th>
                        <th style="padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Deskripsi</th>
                        <th style="padding: 0.75rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Waktu</th>
                    </tr>
                </thead>
                <tbody style="background-color: #ffffff;">
                    @forelse($activities as $activity)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem 1.5rem;">
                            <div style="width: 1.75rem; height: 1.75rem; border-radius: 9999px; background-color: #059669; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 1rem; height: 1rem; color: #ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </td>
                        <td style="padding: 1rem 1.5rem; font-size: 0.875rem; font-weight: 700; color: #1e293b;">
                            {{ $activity->log_name ?? 'Aktivitas Sistem' }}
                        </td>
                        <td style="padding: 1rem 1.5rem; font-size: 0.875rem; color: #475569;">
                            <span style="font-style: italic;">{{ $activity->causer ? $activity->causer->name : 'Sistem' }}</span> 
                            {{ $activity->description }}
                        </td>
                        <td style="padding: 1rem 1.5rem; font-size: 0.75rem; color: #64748b; text-align: right;">
                            {{ \Carbon\Carbon::createFromTimestamp($activity->created_at)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 1rem 1.5rem; text-align: center; font-size: 0.875rem; color: #64748b;">
                            Belum ada log aktivitas terbaru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem; border-top: 1px solid #e2e8f0; background-color: #f8fafc; text-align: center; border-radius: 0 0 0.5rem 0.5rem;">
            <a href="#" style="font-size: 0.875rem; font-weight: 700; color: #059669; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                Lihat Semua Aktivitas
                <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
