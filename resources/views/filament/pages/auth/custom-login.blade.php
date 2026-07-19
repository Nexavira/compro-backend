@push('styles')
    @vite('resources/css/app.css')
    <style>
        /* Mencegah pergeseran tinggi form saat ada pesan error inline */
        .fi-fo-field {
            position: relative;
            padding-bottom: 1.5rem !important; /* Ruang khusus untuk pesan error */
        }
        .fi-fo-field-wrp-error-message, .fi-fo-field-wrp-error-list {
            position: absolute !important;
            bottom: 0;
            left: 0;
            margin-top: 0 !important;
        }
    </style>
@endpush
<div class="relative flex min-h-screen w-full items-center justify-center p-4 sm:p-8">
    <img src="{{ asset('images/login-bg.png') }}" alt="Background" class="absolute inset-0 w-full h-full object-cover" />
    <div class="absolute inset-0 bg-gray-50/80 dark:bg-gray-900/90 backdrop-blur-lg"></div>
    <div class="relative w-full max-w-[1280px] min-h-[700px] z-10 flex">
        <div class="flex flex-col lg:flex-row w-full bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] overflow-hidden relative border border-white/50 dark:border-white/10">
        <div class="flex flex-col justify-center w-full lg:w-5/12 p-10 lg:p-14 xl:p-20 relative bg-white dark:bg-gray-800">
            <div class="flex items-center gap-3 mb-12">
                <div class="p-2.5 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-600/30">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">TerasSapa</span>
            </div>
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Sign in</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Silakan masukkan kredensial Anda untuk masuk ke admin.</p>
            </div>
            <form wire:submit="authenticate" class="flex-grow flex flex-col justify-center" novalidate>
                {{ $this->form }}
                <div class="mt-10">
                    <x-filament::button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3 text-lg font-semibold text-white dark:text-white shadow-lg shadow-emerald-600/30">
                        Masuk
                    </x-filament::button>
                </div>
            </form>
            <div class="mt-12 text-sm text-gray-400 dark:text-gray-500 text-center lg:text-left font-medium">
                &copy; {{ date('Y') }} Nexavira. All rights reserved.
            </div>
        </div>
        <div class="hidden lg:flex lg:w-7/12 relative bg-emerald-900 p-12 xl:p-16 flex-col justify-center items-center">
            <img
                src="{{ asset('images/login-bg.png') }}"
                alt="TerasSapa Enterprise"
                class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay" />
            <div class="absolute inset-0 bg-gradient-to-br from-[#022c22]/95 via-[#064e3b]/85 to-[#047857]/70"></div>
            <div class="relative z-10 w-full max-w-2xl text-center">
                <h2 class="text-5xl font-bold text-white leading-tight mb-6 tracking-tight">
                    Platform Managed SaaS <br />
                    <span class="text-emerald-400">Company Profile</span>
                </h2>
                <p class="text-lg text-emerald-100/90 leading-relaxed mb-10 font-light">
                    Kelola infrastruktur konten klien Anda secara terpusat dengan desain layout yang terkunci untuk menjaga standar premium.
                </p>
                <div class="w-full h-64 bg-white/5 border border-white/10 rounded-2xl mb-10 flex items-center justify-center overflow-hidden shadow-2xl relative group backdrop-blur-sm">
                    <div class="absolute inset-0 bg-gradient-to-tr from-emerald-900/40 to-transparent"></div>
                    <span class="text-emerald-100 text-base flex flex-col items-center gap-3 relative z-10 font-medium tracking-wide">
                        <svg class="w-10 h-10 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        (Tempat Screenshot Landing Page TerasSapa)
                    </span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-5 text-sm font-medium text-emerald-50">
                    <div class="flex items-center gap-2.5 bg-emerald-900/50 px-5 py-2.5 rounded-full border border-emerald-500/20 backdrop-blur-md shadow-inner">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Enterprise Security
                    </div>
                    <div class="flex items-center gap-2.5 bg-emerald-900/50 px-5 py-2.5 rounded-full border border-emerald-500/20 backdrop-blur-md shadow-inner">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        High Performance
                    </div>
                </div>
            </div>
        </div>
        <a href="https://wa.me/6281234567890" target="_blank" class="absolute bottom-8 right-8 p-3.5 bg-[#25D366] text-white rounded-full shadow-[0_4px_14px_0_rgba(37,211,102,0.39)] hover:bg-[#1ebd5a] hover:shadow-[0_6px_20px_rgba(37,211,102,0.4)] hover:scale-110 transition-all duration-300 z-50 flex items-center justify-center group border border-[#25D366]/50">
            <span class="absolute right-full mr-4 px-4 py-2 bg-gray-900 dark:bg-black text-white text-sm font-medium rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 whitespace-nowrap pointer-events-none">
                Hubungi Admin via WhatsApp
                <span class="absolute top-1/2 -right-1.5 -translate-y-1/2 border-[6px] border-transparent border-l-gray-900 dark:border-l-black"></span>
            </span>
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
            </svg>
        </a>
    </div>
</div>