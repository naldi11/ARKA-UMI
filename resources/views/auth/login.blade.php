<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — ARKA UMI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        .auth-gradient { background: radial-gradient(circle at top right, #1e3a8a 0%, #000000 100%); }
        .glass-effect { background: rgba(255, 255, 255, 0.03); backdrop-blur: 10px; border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen antialiased bg-surface-container-lowest">

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- ======= PANEL KIRI (Premium Branding) ======= -->
    <div class="auth-gradient hidden lg:flex lg:w-1/2 xl:w-[55%] flex-col justify-between p-16 relative overflow-hidden shrink-0">
        
        <!-- Animated Background Elements -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-accent-indigo/10 rounded-full blur-[100px] translate-y-1/3 -translate-x-1/4"></div>

        <div class="relative z-10 flex items-center gap-4">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-lg border border-white/20 p-2">
                <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="Logo UMI" class="w-full h-full object-contain">
            </div>
            <div>
                <div class="text-white font-black text-2xl tracking-tighter leading-none">ARKA UMI</div>
                <div class="text-primary-fixed-dim text-xs font-bold tracking-[0.2em] uppercase mt-1 opacity-70">Arsip Akuntansi</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 max-w-xl">
            <div class="inline-flex items-center gap-2.5 bg-white/10 border border-white/10 rounded-full px-4 py-1.5 mb-8 backdrop-blur-md">
                <span class="w-2.5 h-2.5 rounded-full bg-accent-emerald animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></span>
                <span class="text-white/90 text-xs font-bold uppercase tracking-widest">Sistem Aktif & Terenkripsi</span>
            </div>

            <h1 class="text-5xl xl:text-7xl font-black text-white leading-[1.1] tracking-tight mb-6">
                Manajemen Arsip<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-fixed-dim to-accent-indigo">Akuntansi Modern</span>
            </h1>

            <p class="text-primary-fixed-dim text-lg leading-relaxed mb-12 opacity-80 font-medium">
                Platform terpadu untuk pengajuan judul, bimbingan dosen, dan pengarsipan skripsi digital di Universitas Methodist Indonesia.
            </p>

            <!-- Feature Cards -->
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['icon' => 'verified', 'title' => 'Terverifikasi', 'desc' => 'Validasi judul instan'],
                    ['icon' => 'cloud_sync', 'title' => 'Cloud Sync', 'desc' => 'Arsip aman 24/7'],
                ] as $feat)
                <div class="glass-effect p-6 rounded-3xl">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mb-4 border border-white/10">
                        <span class="material-symbols-outlined text-white text-xl">{{ $feat['icon'] }}</span>
                    </div>
                    <div class="text-white font-black text-sm mb-1 uppercase tracking-tight">{{ $feat['title'] }}</div>
                    <div class="text-white/50 text-xs font-medium">{{ $feat['desc'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 flex items-center justify-between border-t border-white/10 pt-8 mt-12">
            <p class="text-white/30 text-xs font-bold tracking-widest uppercase">Fakultas Ekonomi — UMI &copy; {{ date('Y') }}</p>
            <div class="flex gap-4">
                <div class="w-2 h-2 rounded-full bg-white/10"></div>
                <div class="w-2 h-2 rounded-full bg-white/10"></div>
                <div class="w-2 h-2 rounded-full bg-white/30"></div>
            </div>
        </div>
    </div>

    <!-- ======= PANEL KANAN (Form Area) ======= -->
    <div class="flex-1 flex flex-col bg-white overflow-y-auto">

        <div class="lg:hidden auth-gradient px-8 py-8 flex items-center gap-4">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm p-2">
                <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="Logo UMI" class="w-full h-full object-contain">
            </div>
            <div>
                <div class="text-white font-black text-xl tracking-tight leading-none">ARKA UMI</div>
                <div class="text-primary-fixed-dim text-[10px] font-bold uppercase tracking-widest mt-1 opacity-70">Arsip Akuntansi</div>
            </div>
        </div>

        <!-- Form Center -->
        <div class="flex-1 flex items-center justify-center p-8 sm:p-16 lg:p-24">
            <div class="w-full max-w-md">

                <!-- Heading -->
                <div class="mb-12">
                    <h2 class="text-4xl font-black text-on-surface tracking-tighter mb-3">Selamat Datang Kembali</h2>
                    <p class="text-on-surface-variant font-medium">Masuk untuk mengelola progres akademik Anda.</p>
                </div>

                <!-- Alerts -->
                @if(session('status'))
                <div class="flex items-center gap-4 p-4 bg-success/10 border border-success/20 rounded-2xl mb-8 text-sm text-success font-bold">
                    <span class="material-symbols-outlined font-variation-settings: 'FILL' 1;">check_circle</span>
                    {{ session('status') }}
                </div>
                @endif

                @if($errors->any())
                <div class="p-5 bg-error/5 border border-error/10 rounded-2xl mb-8 space-y-2">
                    @foreach($errors->all() as $err)
                    <div class="text-sm text-error font-bold flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                        {{ $err }}
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1" for="username">Identitas / NIM</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-on-surface-variant group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">person_outline</span>
                            </span>
                            <input
                                id="username" name="username" type="text"
                                value="{{ old('username') }}"
                                placeholder="Masukkan NIM atau Username"
                                required autofocus
                                class="w-full pl-12 pr-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div x-data="{ show: false }" class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest" for="password">Kata Sandi</label>
                        </div>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-on-surface-variant group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined">lock_open</span>
                            </span>
                            <input
                                id="password" name="password"
                                :type="show ? 'text' : 'password'"
                                placeholder="••••••••"
                                required
                                class="w-full pl-12 pr-14 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                            >
                            <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            class="w-full py-4 bg-primary text-on-primary font-black text-base rounded-2xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3">
                        Masuk Dashboard
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>

                <!-- Navigation Links -->
                <div class="mt-12 text-center space-y-6">
                    <p class="text-on-surface-variant font-bold text-sm">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="text-primary hover:underline underline-offset-4 ml-1">Daftar Mahasiswa</a>
                    </p>

                    <div class="flex items-center gap-4">
                        <div class="flex-1 h-px bg-outline-variant/30"></div>
                        <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.3em]">Atau</span>
                        <div class="flex-1 h-px bg-outline-variant/30"></div>
                    </div>

                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-black text-on-surface-variant hover:text-primary transition-colors uppercase tracking-widest">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Kembali ke Portal
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

</body>
</html>

