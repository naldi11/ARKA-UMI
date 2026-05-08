<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Mahasiswa — ARKA UMI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        .auth-gradient { background: radial-gradient(circle at bottom left, #1e3a8a 0%, #000000 100%); }
        .glass-effect { background: rgba(255, 255, 255, 0.03); backdrop-blur: 10px; border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen antialiased bg-surface-container-lowest">

<div class="min-h-screen flex flex-col lg:flex-row">

    <!-- ======= PANEL KIRI (Premium Branding) ======= -->
    <div class="auth-gradient hidden lg:flex lg:w-5/12 xl:w-2/5 flex-col justify-between p-16 relative overflow-hidden shrink-0">
        
        <!-- Animated Background Elements -->
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-primary/20 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/3 animate-pulse"></div>
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-accent-indigo/10 rounded-full blur-[100px] -translate-y-1/3 translate-x-1/4"></div>

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
        <div class="relative z-10">
            <h1 class="text-5xl xl:text-6xl font-black text-white leading-[1.1] tracking-tight mb-8">
                Mulai Perjalanan<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-fixed-dim to-accent-indigo">Akademik Digital</span>
            </h1>

            <p class="text-primary-fixed-dim text-lg leading-relaxed mb-12 opacity-80 font-medium max-w-sm">
                Buat akun mahasiswa untuk mengakses pengajuan judul skripsi, bimbingan dospem, dan arsip digital Universitas Methodist Indonesia.
            </p>

            <!-- Feature List -->
            <div class="space-y-4">
                @foreach([
                    ['icon' => 'fact_check', 'text' => 'Ajukan hingga 5 judul skripsi'],
                    ['icon' => 'monitoring', 'text' => 'Pantau progress bimbingan real-time'],
                    ['icon' => 'shield_check', 'text' => 'Penyimpanan dokumen terarsip aman'],
                ] as $item)
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center backdrop-blur-md">
                        <span class="material-symbols-outlined text-primary-fixed-dim text-lg">{{ $item['icon'] }}</span>
                    </div>
                    <span class="text-white/80 font-bold text-sm tracking-wide">{{ $item['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10">
            <p class="text-white/30 text-xs font-bold tracking-widest uppercase">Arsip Akuntansi UMI &copy; {{ date('Y') }}</p>
        </div>
    </div>

    <!-- ======= PANEL KANAN (Registration Form) ======= -->
    <div class="flex-1 flex flex-col bg-white overflow-y-auto">

        <div class="lg:hidden auth-gradient px-8 py-8 flex items-center gap-4">
            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm p-2">
                <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="Logo UMI" class="w-full h-full object-contain">
            </div>
            <div class="text-white">
                <div class="font-black text-xl tracking-tight leading-none">ARKA UMI</div>
                <div class="text-primary-fixed-dim text-[10px] font-bold uppercase tracking-widest mt-1 opacity-70">Arsip Akuntansi</div>
            </div>
        </div>

        <!-- Form Area -->
        <div class="flex-1 flex items-center justify-center p-8 sm:p-16 lg:p-20">
            <div class="w-full max-w-xl">

                <!-- Heading -->
                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-4xl font-black text-on-surface tracking-tighter mb-3">Buat Akun Baru</h2>
                    <p class="text-on-surface-variant font-medium">Lengkapi formulir berikut untuk bergabung ke sistem ARKA UMI.</p>
                </div>

                <!-- Error Messages -->
                @if(session('error'))
                <div class="flex items-center gap-4 p-4 bg-error/5 border border-error/10 rounded-2xl mb-8 text-sm text-error font-bold">
                    <span class="material-symbols-outlined font-variation-settings: 'FILL' 1;">error</span>
                    {{ session('error') }}
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Username</label>
                            <input
                                name="username" type="text"
                                value="{{ old('username') }}"
                                placeholder="budi_akuntansi"
                                required
                                class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                            >
                            @error('username') <p class="text-error text-xs font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nama Lengkap</label>
                            <input
                                name="name" type="text"
                                value="{{ old('name') }}"
                                placeholder="Budi Santoso"
                                required
                                class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                            >
                            @error('name') <p class="text-error text-xs font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Email Institusi</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant">mail</span>
                            <input
                                name="email" type="email"
                                value="{{ old('email') }}"
                                placeholder="nim@mahasiswa.umi.ac.id"
                                required
                                class="w-full pl-12 pr-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                            >
                        </div>
                        @error('email') <p class="text-error text-xs font-bold mt-1 px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Nomor Induk Mahasiswa (NIM)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant">badge</span>
                            <input
                                name="nim" type="text"
                                value="{{ old('nim') }}"
                                placeholder="10 Digit NIM"
                                required
                                class="w-full pl-12 pr-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-black tracking-widest placeholder:text-on-surface-variant/40 placeholder:tracking-normal transition-all outline-none"
                            >
                        </div>
                        @error('nim') <p class="text-error text-xs font-bold mt-1 px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ show1: false, show2: false }">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Kata Sandi</label>
                            <div class="relative">
                                <input
                                    name="password"
                                    :type="show1 ? 'text' : 'password'"
                                    placeholder="Min. 8 Karakter"
                                    required
                                    class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                                >
                                <button type="button" @click="show1 = !show1" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl" x-text="show1 ? 'visibility_off' : 'visibility'">visibility</span>
                                </button>
                            </div>
                            @error('password') <p class="text-error text-xs font-bold mt-1 px-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Konfirmasi Sandi</label>
                            <div class="relative">
                                <input
                                    name="password_confirmation"
                                    :type="show2 ? 'text' : 'password'"
                                    placeholder="Ulangi Sandi"
                                    required
                                    class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/40 transition-all outline-none"
                                >
                                <button type="button" @click="show2 = !show2" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl" x-text="show2 ? 'visibility_off' : 'visibility'">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-4 bg-primary text-on-primary font-black text-base rounded-2xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3"
                    >
                        Daftar Akun
                        <span class="material-symbols-outlined">how_to_reg</span>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-10 text-center">
                    <p class="text-on-surface-variant font-bold text-sm mb-6">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="text-primary hover:underline underline-offset-4 ml-1">Masuk Sekarang</a>
                    </p>

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

