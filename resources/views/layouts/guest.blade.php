<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ARKA UMI') }} - @yield('title', 'Academic Precision')</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js & Livewire Styles -->
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-slate-50 font-body text-slate-900 antialiased selection:bg-blue-100">
    
    <!-- Guest Layout Container: Membagi layar menjadi section visual dan form -->
    <main class="min-h-screen flex flex-col md:flex-row">
        
        <!-- Section Visual (Kiri) -->
        <!-- Digital Curator: Menggunakan Organic Structuralism dengan tonal layering -->
        <section class="relative hidden md:flex md:w-1/2 lg:w-3/5 bg-blue-100 items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-primary-dim/30 z-10"></div>
            
            <!-- Gambar Background dengan mix-blend-mode untuk kesan editorial -->
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDulG8K7KxvWF_WfEjTCDDNcRlcVTvS78vwwsEP4s9gpMdDcKt46kjIgW36146rZM1wyzGNBi_KIxY1I9Ei5rJeY2ffgOD3nsEYk23vGMKP60vf1thdcisdr4Kh7nhIBha3U7UxnYdCTAggaG9G6rThZPuwtQV6z4p_R99fB8vRdFbqMXAvq19tCFfUFokLd1AORALtTqJquTRCii9UiOJ2jen2htEHNDioq-HZWMweznqNrsWK3t1_OwcGoDlKkRbj0wATNZUUzSY" 
                 class="absolute inset-0 object-cover w-full h-full opacity-40 mix-blend-overlay" 
                 alt="Academic environment">

            <div class="relative z-20 p-12 text-center flex flex-col items-center">
                <!-- Brand Icon -->
                <div class="mb-8 w-24 h-24 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-blue-700 text-5xl">school</span>
                </div>
                
                <h1 class="text-4xl lg:text-5xl font-black text-blue-900 tracking-tighter mb-4">ARKA UMI</h1>
                <p class="text-lg lg:text-xl text-white-fixed-variant font-medium max-w-md mx-auto leading-relaxed">
                    Precision accounting thesis management. Where academic rigor meets digital governance.
                </p>

                <!-- Floating Glass Card (Organic Structuralism Philosophy) -->
                <div class="mt-12 p-6 bg-white/40 backdrop-blur-xl rounded-xl border border-white/20 shadow-xl max-w-sm">
                    <p class="text-sm font-medium text-slate-900 italic">"Excellence in research begins with organized stewardship."</p>
                    <div class="mt-4 flex items-center justify-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-900"></div>
                        <div class="w-2 h-2 rounded-full bg-blue-900/40"></div>
                        <div class="w-2 h-2 rounded-full bg-blue-900/20"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Form Konten (Kanan) -->
        <section class="flex-1 flex flex-col justify-center items-center p-6 md:p-12 lg:p-24 bg-white">
            {{ $slot ?? '' }}
            @yield('content')
        </section>
        
    </main>

    <!-- Footer Mobile/Floating -->
    <div class="fixed bottom-6 left-0 right-0 flex justify-center md:hidden lg:flex px-6 pointer-events-none">
        <div class="bg-white/80 backdrop-blur-md px-6 py-2 rounded-full border border-slate-200 shadow-sm pointer-events-auto">
            <p class="text-[10px] font-normal uppercase tracking-[0.2em] text-slate-400">
                © 2024 ARKA UMI. Academic Excellence.
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
