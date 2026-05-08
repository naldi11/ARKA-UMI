<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ARKA UMI — @yield('title', 'Panel Kontrol')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        .sidebar-link { @apply flex items-center gap-3 px-4 py-3 text-sm rounded-xl transition-all duration-200; }
        .sidebar-link.active { @apply bg-primary text-on-primary font-bold shadow-lg shadow-primary/20 scale-[1.02]; }
        .sidebar-link:not(.active) { @apply text-on-surface-variant font-medium hover:bg-surface-container-high hover:text-on-surface; }
        
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: theme('colors.outline-variant'); border-radius: 99px; }

        /* Select2 Premium Styling */
        .select2-container--default .select2-selection--single {
            @apply bg-surface-container-low border-transparent rounded-2xl h-[58px] flex items-center px-4 transition-all;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            @apply text-sm font-bold text-on-surface p-0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            @apply h-full right-4 flex items-center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            @apply border-t-on-surface-variant/40 border-t-4 border-x-transparent border-x-4 border-b-0;
        }
        .select2-container--open .select2-dropdown {
            @apply border-outline-variant/10 shadow-premium-lg rounded-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200 mt-2;
        }
        .select2-search--dropdown .select2-search__field {
            @apply bg-surface-container-high border-transparent rounded-xl px-4 py-3 text-sm font-bold outline-none mb-2 focus:bg-white focus:ring-4 focus:ring-primary/10 transition-all;
        }
        .select2-results__option {
            @apply px-5 py-3 text-sm font-bold text-on-surface-variant transition-colors;
        }
        .select2-results__option--highlighted[aria-selected] {
            @apply bg-primary text-on-primary;
        }
        .select2-results__option[aria-selected=true] {
            @apply bg-primary/10 text-primary;
        }
    </style>
</head>
<body class="bg-surface-container-lowest text-on-surface antialiased selection:bg-primary-container selection:text-on-primary-container" x-data="{ sidebarOpen: false }">

    <!-- ===================== TOPBAR ===================== -->
    <header class="fixed top-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-outline-variant/10 h-16 flex items-center px-6 gap-4 shadow-sm md:left-64 left-0 transition-all duration-300">

        <!-- Hamburger (mobile only) -->
        <button @click="sidebarOpen = true" class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl hover:bg-surface-container-high transition text-on-surface-variant">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- Brand (Only on mobile topbar) -->
        <a href="{{ route('dashboard') }}" class="md:hidden flex items-center gap-3 shrink-0 group">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-outline-variant/10">
                <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="UMI" class="w-7 h-7 object-contain">
            </div>
        </a>

        <!-- Spacer -->
        <div class="flex-1"></div>

        <!-- Notifikasi -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false"
                    class="relative flex items-center justify-center w-10 h-10 rounded-xl hover:bg-surface-container-high transition text-on-surface-variant">
                <span class="material-symbols-outlined">notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-white animate-pulse"></span>
                @endif
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-premium-lg border border-outline-variant/20 overflow-hidden z-50">
                <div class="px-5 py-4 border-b border-outline-variant/10 flex justify-between items-center bg-surface-container-low">
                    <span class="text-sm font-bold text-on-surface">Notifikasi</span>
                    <span class="text-[10px] bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full font-bold">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                </div>
                <div class="max-h-80 overflow-y-auto divide-y divide-outline-variant/10">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                    <div class="px-5 py-4 hover:bg-surface-container-lowest transition cursor-pointer">
                        <p class="text-xs text-on-surface leading-snug">{{ $notif->data['message'] }}</p>
                        <p class="text-[10px] text-on-surface-variant mt-2 font-medium">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <div class="px-5 py-10 text-center">
                        <div class="w-12 h-12 bg-surface-container rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-outline">notifications_off</span>
                        </div>
                        <p class="text-xs text-on-surface-variant font-medium">Tidak ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- User & Logout -->
        <div class="flex items-center gap-3 pl-3 border-l border-outline-variant/20">
            <div class="hidden sm:block text-right">
                <div class="text-sm font-bold text-on-surface leading-none">{{ \Illuminate\Support\Str::limit(Auth::user()->name, 18) }}</div>
                <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider mt-1">
                    @if(Auth::user()->role === 'admin') Administrator @elseif(Auth::user()->role === 'dosen') Dosen @else Mahasiswa @endif
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-black text-sm shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->name, -1, 1)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-xl text-error hover:bg-error-container hover:text-on-error-container transition-all" title="Keluar">
                    <span class="material-symbols-outlined">logout</span>
                </button>
            </form>
        </div>
    </header>

    <!-- ===================== MOBILE OVERLAY ===================== -->
    <div x-show="sidebarOpen" x-cloak
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] bg-on-surface/20 backdrop-blur-sm md:hidden"></div>

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="fixed left-0 top-0 bottom-0 z-[70] w-64 bg-white border-r border-outline-variant/10 flex flex-col
                  transform transition-transform duration-300 ease-in-out
                  -translate-x-full md:translate-x-0 m-0"
           :class="sidebarOpen ? '!translate-x-0' : ''">

        <!-- Sidebar header -->
        <div class="h-16 flex items-center px-6 border-b border-outline-variant/10 gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-outline-variant/10">
                <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="UMI" class="w-7 h-7 object-contain">
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-black text-primary leading-none uppercase tracking-tighter">ARKA UMI</div>
                <div class="text-[9px] text-on-surface-variant uppercase font-bold tracking-widest mt-1">Arsip Akuntansi</div>
            </div>
            <!-- Close button mobile -->
            <button @click="sidebarOpen = false" class="md:hidden w-8 h-8 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container-high">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- User info card -->
        <div class="px-4 py-6">
            <div class="bg-surface-container-low rounded-2xl p-4 flex items-center gap-4 border border-outline-variant/10">
                <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-on-primary font-black text-lg shadow-lg shadow-primary/20">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-black text-on-surface truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">
                        {{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'dosen' ? 'Dosen Pembimbing' : 'Mahasiswa') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-1">
            <div class="px-4 mb-2">
                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Menu Utama</p>
            </div>
            @include('layouts.navigation')
        </nav>

        <!-- Sidebar footer -->
        <div class="p-4 mt-auto">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface rounded-xl transition-all group">
                <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">public</span>
                <span>Portal Publik</span>
            </a>
        </div>
    </aside>

    <!-- ===================== MAIN CONTENT ===================== -->
    <main class="md:pl-64 pt-16 min-h-screen bg-surface-container-lowest flex flex-col transition-all duration-300">

        <!-- Flash Messages -->
        <div class="fixed top-20 left-1/2 -translate-x-1/2 z-[100] flex flex-col gap-3 pointer-events-none w-full max-w-sm px-4">
            @if(session('status'))
            <div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="pointer-events-auto flex items-center gap-4 px-5 py-4 bg-success text-white rounded-2xl shadow-premium-lg">
                <span class="material-symbols-outlined font-variation-settings: 'FILL' 1;">check_circle</span>
                <div class="flex-1 font-bold text-sm">{{ session('status') }}</div>
                <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg transition">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="pointer-events-auto flex items-center gap-4 px-5 py-4 bg-error text-white rounded-2xl shadow-premium-lg">
                <span class="material-symbols-outlined font-variation-settings: 'FILL' 1;">error</span>
                <div class="flex-1 font-bold text-sm">{{ session('error') }}</div>
                <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg transition">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="flex-1 px-4 sm:px-8 py-8">
            @yield('content')
            {{ $slot ?? '' }}
        </div>

        <!-- Footer -->
        <footer class="px-6 sm:px-10 pb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white px-6 py-4 rounded-2xl border border-outline-variant/10 shadow-sm text-xs text-on-surface-variant font-bold uppercase tracking-wider">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-2 text-success">
                        <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                        Sistem Aktif
                    </span>
                    <span class="text-outline-variant/30">•</span>
                    <span>v2.0.4 Premium</span>
                </div>
                <div>ARKA UMI &copy; {{ date('Y') }} — FE Universitas Methodist Indonesia</div>
            </div>
        </footer>
    </main>
    @stack('scripts')
</body>
</html>
