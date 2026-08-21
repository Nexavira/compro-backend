<x-filament-widgets::widget>
    <x-filament::section class="fi-wi-welcome-banner" style="background-color: #f8fafc; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); padding: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; background-color: #d1fae5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg style="width: 1.25rem; height: 1.25rem; color: #065f46;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; letter-spacing: -0.025em; line-height: 1.2;">
                        Selamat datang, <span style="color: #047857;">{{ auth()->user()->name ?? 'Pengguna' }}!</span>
                    </h2>
                    <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem; font-weight: 500;">
                        Anda masuk sebagai <strong>{{ auth()->user()->roleUser?->role?->name ?? 'Admin' }}</strong>. Berikut ringkasan sistem hari ini.
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; background-color: #f1f5f9; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                <svg style="width: 1.25rem; height: 1.25rem; color: #047857;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span style="font-size: 0.875rem; font-weight: 600; color: #334155;">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>

        @if(auth()->user()->roleUser?->role?->code === 'tenant_admin')
            @php
                $tenant = auth()->user()->userDetail?->tenant;
            @endphp
            @if($tenant && $tenant->concierge_status !== 'completed')
                <div style="margin-top: 1rem; padding: 1rem; background-color: #ecfdf5; border: 1px dashed #34d399; border-radius: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 style="font-weight: 700; color: #065f46; margin-bottom: 0.25rem;">Butuh Bantuan Setup? (Layanan Concierge)</h3>
                        <p style="font-size: 0.875rem; color: #047857;">Dapatkan bantuan setup website secara gratis dari tim profesional kami. Cukup kirimkan permintaan!</p>
                    </div>
                    <div>
                        @if($tenant->concierge_status === 'requested')
                            <span style="background-color: #059669; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600;">Permintaan Terkirim - Menunggu Tim Kami</span>
                        @else
                            <button wire:click="requestConcierge" wire:loading.attr="disabled" style="background-color: #059669; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.2s;">
                                Minta Setup Gratis
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
