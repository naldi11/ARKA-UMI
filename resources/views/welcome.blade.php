<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ARKA UMI — Perpustakaan Digital Riset Akuntansi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        
        .thesis-card { @apply transition-all duration-300; }
        .thesis-card.hidden-by-search { display: none; }
        
        .text-gradient {
            @apply bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent-indigo;
        }
    </style>
</head>
<body class="bg-surface-container-lowest text-on-surface selection:bg-primary-container selection:text-on-primary-container antialiased">

    <!-- ===================== NAVIGASI ===================== -->
    <header class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-xl border-b border-outline-variant/10">
        <div class="flex justify-between items-center w-full px-6 py-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-3 group cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-outline-variant/10 group-hover:rotate-6 transition-transform">
                    <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="Logo UMI" class="w-7 h-7 object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-black text-primary leading-none tracking-tighter">ARKA UMI</span>
                    <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-[0.2em] mt-1">Arsip Akuntansi</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-bold text-primary bg-primary/5 rounded-xl transition-all">Telusuri Riset</a>
                <a href="{{ route('guidelines') }}" class="px-4 py-2 text-sm font-bold text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high rounded-xl transition-all">Panduan Sistem</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        Panel Kontrol
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-bold text-on-surface-variant hover:text-primary transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary shadow-premium">
                        Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="pt-20">

        <!-- ===================== HERO SECTION ===================== -->
        <section class="hero-pattern relative overflow-hidden pt-24 pb-20 border-b border-outline-variant/10">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-accent-indigo/5 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2"></div>

            <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-outline-variant/20 rounded-full shadow-sm mb-8">
                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                    <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Akses Digital Terintegrasi</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black tracking-tight text-on-surface mb-8 leading-[1.1]">
                    Eksplorasi Riset<br>
                    <span class="text-gradient">Akuntansi Modern</span>
                </h1>
                
                <p class="text-on-surface-variant text-lg md:text-xl font-medium mb-12 max-w-2xl mx-auto leading-relaxed">
                    Platform kurasi skripsi akuntansi dengan standar tata kelola digital. 
                    Akses data tervalidasi dari <span class="text-primary font-bold">3 tahun terakhir</span> untuk referensi riset berkualitas.
                </p>

                <!-- Pencarian Utama -->
                <div class="relative max-w-2xl mx-auto group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-on-surface-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[28px]">search</span>
                    </div>
                    <input id="liveSearchInput"
                           class="block w-full pl-16 pr-16 py-6 bg-white rounded-[24px] shadow-premium border-none focus:ring-4 focus:ring-primary/10 text-lg font-bold text-on-surface placeholder:text-on-surface-variant/40 transition-all group-hover:shadow-premium-lg"
                           placeholder="Cari judul, nama mahasiswa, atau kata kunci..."
                           type="text"
                           autocomplete="off"/>
                    
                    <button id="clearSearchBtn"
                            onclick="clearSearch()"
                            class="absolute right-5 top-1/2 -translate-y-1/2 hidden w-10 h-10 bg-surface-container-high hover:bg-error/10 hover:text-error rounded-xl flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="mt-6">
                    <span id="searchStats" class="text-xs font-bold text-on-surface-variant uppercase tracking-widest opacity-60">
                        Total <strong>{{ $theses->count() }}</strong> skripsi tervalidasi tersedia
                    </span>
                </div>
            </div>
        </section>

        <!-- ===================== GRID KONTEN ===================== -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            
            <!-- Filter & Sort (Visual Only for now) -->
            <div class="flex justify-between items-center mb-10 pb-6 border-b border-outline-variant/10">
                <h2 class="text-xl font-black text-on-surface tracking-tight flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">auto_stories</span>
                    Arsip Terbaru
                </h2>
                <div class="flex gap-2">
                    <div class="px-3 py-1.5 bg-surface-container-high rounded-lg text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Urutan: Terbaru</div>
                </div>
            </div>

            <!-- Pesan Jika Kosong -->
            <div id="noResults" class="hidden py-32 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-surface-container rounded-[32px] flex items-center justify-center mb-6 text-outline-variant">
                    <span class="material-symbols-outlined text-5xl">search_off</span>
                </div>
                <p class="text-xl font-black text-on-surface tracking-tight">Data Tidak Ditemukan</p>
                <p class="text-sm mt-2 text-on-surface-variant font-medium">Coba gunakan kata kunci lain atau periksa ejaan Anda.</p>
            </div>

            @if($theses->count() > 0)
            <div id="thesisGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($theses as $thesis)
                <div class="thesis-card group bg-white p-8 rounded-[32px] border border-outline-variant/10 shadow-sm hover:shadow-premium-lg hover:-translate-y-1 transition-all cursor-pointer relative overflow-hidden"
                     onclick="window.location.href='{{ route('thesis.show', $thesis->id) }}'"
                     data-title="{{ strtolower($thesis->title) }}"
                     data-author="{{ strtolower($thesis->mahasiswa->user->name) }}"
                     data-journal="{{ strtolower($thesis->jurnal_name) }}">

                    <div class="flex justify-between items-start mb-6">
                        <span class="px-3 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-black uppercase tracking-widest rounded-lg">Terarsip</span>
                        <div class="flex items-center gap-1.5 text-on-surface-variant/40">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            <span class="text-xs font-bold">{{ $thesis->approved_at ? $thesis->approved_at->format('M Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    <h3 class="text-lg font-black text-on-surface group-hover:text-primary transition-colors leading-snug mb-6 line-clamp-3 tracking-tight">
                        {{ $thesis->title }}
                    </h3>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3 text-sm text-on-surface-variant font-medium">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </div>
                            <span class="truncate">{{ $thesis->mahasiswa->user->name }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-on-surface-variant font-medium">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                            </div>
                            <span class="truncate">{{ $thesis->jurnal_name }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-outline-variant/10">
                        <div class="flex items-center gap-2 text-success">
                            <span class="material-symbols-outlined text-[18px] font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Terverifikasi</span>
                        </div>
                        <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </div>
                    </div>
                    
                    <!-- Decorative Gradient -->
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-primary opacity-[0.02] rounded-full blur-3xl"></div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-32 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-surface-container rounded-[32px] flex items-center justify-center mb-6 text-outline-variant">
                    <span class="material-symbols-outlined text-5xl">inbox</span>
                </div>
                <p class="text-xl font-black text-on-surface tracking-tight">Belum Ada Data</p>
                <p class="text-sm mt-2 text-on-surface-variant font-medium">Data skripsi yang telah tervalidasi akan muncul di sini secara otomatis.</p>
            </div>
            @endif

            <!-- Banner Ajakan -->
            <div class="mt-20 p-10 md:p-16 rounded-[48px] bg-primary text-on-primary flex flex-col md:flex-row items-center justify-between gap-10 overflow-hidden relative shadow-premium-lg">
                <div class="z-10 relative text-center md:text-left">
                    <h4 class="text-3xl md:text-4xl font-black tracking-tight mb-4 leading-tight">Belum menemukan yang Anda cari?</h4>
                    <p class="text-primary-container text-lg font-medium max-w-xl opacity-80">
                        Akses database lengkap khusus untuk dosen dan mahasiswa yang terdaftar di sistem ARKA UMI.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 z-10 relative shrink-0">
                    <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-4 rounded-[20px] font-black hover:bg-primary-container transition-all shadow-lg text-center">
                        Daftar Akun
                    </a>
                    <a href="{{ route('login') }}" class="bg-primary-dim border border-white/20 text-white px-8 py-4 rounded-[20px] font-black hover:bg-white/10 transition-all text-center">
                        Masuk Sistem
                    </a>
                </div>
                <!-- Background Accents -->
                <div class="absolute right-0 top-0 w-80 h-80 bg-white/10 rounded-full -mr-20 -mt-20 blur-[100px]"></div>
                <div class="absolute left-0 bottom-0 w-64 h-64 bg-accent-indigo/20 rounded-full -ml-10 -mb-10 blur-[80px]"></div>
            </div>
        </section>
    </main>

    <footer class="w-full py-12 border-t border-outline-variant/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col items-center justify-center gap-6">
                <div class="flex items-center gap-3 grayscale opacity-50">
                    <img src="{{ asset('images/logo-umi-ekonomi.png') }}" alt="Logo UMI" class="w-8 h-8 object-contain">
                    <span class="text-lg font-black text-on-surface-variant tracking-tighter uppercase">ARKA UMI</span>
                </div>
                <p class="text-on-surface-variant text-[10px] font-black uppercase tracking-[0.3em] text-center max-w-lg leading-relaxed">
                    © {{ date('Y') }} ARKA UMI · Keunggulan Akademik melalui Tata Kelola Digital Terpadu · Universitas Methodist Indonesia
                </p>
            </div>
        </div>
    </footer>

    <!-- ===================== SKRIP PENCARIAN ===================== -->
    <script>
        const input       = document.getElementById('liveSearchInput');
        const clearBtn    = document.getElementById('clearSearchBtn');
        const cards       = document.querySelectorAll('.thesis-card');
        const noResults   = document.getElementById('noResults');
        const statsEl     = document.getElementById('searchStats');
        const total       = cards.length;

        function filterTheses() {
            const q = input.value.trim().toLowerCase();

            clearBtn.classList.toggle('hidden', q.length === 0);
            clearBtn.classList.toggle('flex', q.length > 0);

            if (q === '') {
                cards.forEach(c => c.classList.remove('hidden-by-search'));
                noResults.classList.add('hidden');
                statsEl.innerHTML = `Total <strong>${total}</strong> skripsi tervalidasi tersedia`;
                return;
            }

            let visible = 0;
            cards.forEach(card => {
                const title   = card.dataset.title   || '';
                const author  = card.dataset.author  || '';
                const journal = card.dataset.journal || '';

                const match = title.includes(q) || author.includes(q) || journal.includes(q);

                if (match) {
                    card.classList.remove('hidden-by-search');
                    visible++;
                } else {
                    card.classList.add('hidden-by-search');
                }
            });

            noResults.classList.toggle('hidden', visible > 0);
            if (visible > 0) {
                statsEl.innerHTML = `Ditemukan <strong>${visible}</strong> hasil dari ${total} skripsi untuk "<em>${input.value}</em>"`;
            } else {
                statsEl.innerHTML = `Tidak ada hasil untuk "<em>${input.value}</em>"`;
            }
        }

        function clearSearch() {
            input.value = '';
            filterTheses();
            input.focus();
        }

        input.addEventListener('input', filterTheses);
    </script>

</body>
</html>
