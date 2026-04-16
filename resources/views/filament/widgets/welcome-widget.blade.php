<x-filament-widgets::widget>
    <x-filament::section class="border-0 shadow-sm">
        
        <style>
            .welcome-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding: 0.25rem;
            }
            .welcome-left {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .welcome-icon {
                width: 3rem;
                height: 3rem;
                border-radius: 50%;
                background-color: #ccfbf1;
                color: #0d9488;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .welcome-title {
                font-size: 1.25rem;
                font-weight: 700;
                margin: 0;
                color: #111827;
            }
            .welcome-subtitle {
                font-size: 0.875rem;
                color: #6b7280;
                margin: 0;
                margin-top: 0.25rem;
            }
            .welcome-right-box {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                background-color: #f9fafb;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
            }
        </style>

        <div class="welcome-container">
            <div class="welcome-left">
                <div class="welcome-icon">
                    <x-heroicon-s-sparkles style="width: 1.5rem; height: 1.5rem;" />
                </div>
                <div>
                    <h2 class="welcome-title">
                        Selamat datang, <span style="color: #0d9488;">{{ auth()->user()->name ?? 'Admin' }}</span>!
                    </h2>
                    <p class="welcome-subtitle">Ringkasan performa sistem hari ini.</p>
                </div>
            </div>

            <div>
                <div class="welcome-right-box">
                    <x-heroicon-o-calendar-days style="width: 1.25rem; height: 1.25rem; color: #9ca3af;" />
                    <span style="font-size: 0.875rem; font-weight: 500; color: #374151;">
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>