<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Pengajuan Skripsi — ARKA UMI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        .guidelines-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        
        .step-card { @apply transition-all duration-300 hover:shadow-premium-lg hover:-translate-y-1; }
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
                <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-bold text-on-surface-variant hover:text-primary hover:bg-primary/5 rounded-xl transition-all">Telusuri Riset</a>
                <a href="{{ route('guidelines') }}" class="px-4 py-2 text-sm font-bold text-primary bg-primary/5 rounded-xl transition-all">Panduan Sistem</a>
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

    <main class="pt-20 guidelines-pattern">

        <!-- ===================== HERO SECTION ===================== -->
        <section class="relative overflow-hidden pt-24 pb-16">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 text-primary border border-primary/20 rounded-full shadow-sm mb-8">
                    <span class="material-symbols-outlined text-[16px]">menu_book</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Pedoman Resmi Sistem</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-on-surface tracking-tight leading-tight mb-6">
                    Panduan Pengajuan Skripsi
                </h1>
                <p class="text-on-surface-variant text-lg font-medium max-w-2xl mx-auto leading-relaxed">
                    Tata cara, persyaratan, dan alur lengkap pengajuan skripsi melalui sistem ARKA UMI. 
                    Pelajari panduan ini untuk kelancaran administrasi akademik Anda.
                </p>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-6 space-y-12 pb-24">

            <!-- 1. Persyaratan -->
            <div class="premium-card overflow-hidden">
                <div class="bg-surface-container-low px-8 py-6 border-b border-outline-variant/10 flex items-center gap-6">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-on-primary shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-[24px]">assignment</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-on-surface tracking-tight">1. Persyaratan Utama</h2>
                        <p class="text-sm text-on-surface-variant font-medium">Kriteria yang wajib dipenuhi sebelum memulai pengajuan</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php $reqs = [
                            ['NIM terdaftar dalam basis data whitelist ARKA UMI', 'badge'],
                            ['Sudah memiliki akun aktif yang tervalidasi oleh administrator', 'verified_user'],
                            ['Telah menentukan topik penelitian dan target jurnal publikasi', 'title'],
                            ['Memahami format penulisan sesuai pedoman Fakultas Ekonomi', 'description'],
                            ['Menyiapkan dokumen naskah final dalam format PDF', 'upload_file'],
                            ['Memiliki surat pernyataan orisinalitas karya ilmiah', 'shield'],
                        ]; @endphp
                        @foreach($reqs as [$text, $icon])
                        <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                            <div class="w-8 h-8 bg-success/10 text-success rounded-lg flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] font-variation-settings: 'FILL' 1;">check_circle</span>
                            </div>
                            <span class="text-sm font-bold text-on-surface leading-tight">{{ $text }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 2. Alur Proses -->
            <div class="premium-card overflow-hidden">
                <div class="bg-surface-container-low px-8 py-6 border-b border-outline-variant/10 flex items-center gap-6">
                    <div class="w-12 h-12 bg-accent-indigo rounded-2xl flex items-center justify-center text-white shadow-lg shadow-accent-indigo/20">
                        <span class="material-symbols-outlined text-[24px]">account_tree</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-on-surface tracking-tight">2. Alur Proses Akademik</h2>
                        <p class="text-sm text-on-surface-variant font-medium">Tahapan dari awal pengajuan hingga dinyatakan lulus</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        @php $steps = [
                            ['Pengajuan Judul', 'Mahasiswa mengajukan minimal satu opsi judul penelitian melalui panel kontrol.', 'edit_note', 'bg-primary/10 text-primary'],
                            ['Penugasan Pembimbing', 'Admin menugaskan Dosen Pembimbing 1 dan 2 berdasarkan bidang keahlian.', 'assignment_ind', 'bg-accent-indigo/10 text-accent-indigo'],
                            ['Review Dosen Pembimbing', 'Dosen melakukan penelaahan dan memberikan catatan revisi jika diperlukan.', 'rate_review', 'bg-warning/10 text-warning'],
                            ['Persetujuan Judul', 'Judul dinyatakan valid dan disetujui untuk dilanjutkan ke tahap penelitian.', 'verified', 'bg-success/10 text-success'],
                            ['Upload Dokumen Akhir', 'Mahasiswa mengunggah berkas Skripsi, Berita Acara, dan Dokumen Final.', 'cloud_upload', 'bg-accent-indigo/10 text-accent-indigo'],
                            ['Verifikasi & Kelulusan', 'Validasi akhir oleh Admin dan status mahasiswa dinyatakan Lulus.', 'school', 'bg-primary text-white shadow-lg'],
                        ]; @endphp
                        @foreach($steps as $i => [$title, $desc, $icon, $color])
                        <div class="flex items-start gap-6 p-6 rounded-[24px] border border-outline-variant/10 hover:bg-surface-container-low transition-colors group">
                            <div class="w-12 h-12 {{ $color }} rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined">{{ $icon }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-widest">Tahap 0{{ $i+1 }}</span>
                                    <h4 class="text-base font-black text-on-surface">{{ $title }}</h4>
                                </div>
                                <p class="text-sm text-on-surface-variant font-medium leading-relaxed">{{ $desc }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. Pertanyaan Umum -->
            <div class="premium-card overflow-hidden">
                <div class="bg-surface-container-low px-8 py-6 border-b border-outline-variant/10 flex items-center gap-6">
                    <div class="w-12 h-12 bg-on-surface-variant rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <span class="material-symbols-outlined text-[24px]">quiz</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-on-surface tracking-tight">3. Pertanyaan Umum (FAQ)</h2>
                        <p class="text-sm text-on-surface-variant font-medium">Informasi tambahan yang sering ditanyakan</p>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @php $faqs = [
                            ['NIM tidak ditemukan saat registrasi?', 'Pastikan NIM Anda sudah masuk dalam daftar whitelist. Jika belum, hubungi Biro Akuntansi/Admin Prodi.'],
                            ['Berapa lama proses verifikasi akun?', 'Proses verifikasi akun oleh admin biasanya memakan waktu 1-2 hari kerja setelah pendaftaran.'],
                            ['Apakah boleh mengganti judul?', 'Revisi judul dapat dilakukan setelah berdiskusi dengan Dosen Pembimbing dan disetujui melalui sistem.'],
                            ['Format berkas apa yang didukung?', 'Sistem hanya menerima file dalam format PDF dengan ukuran maksimal sesuai ketentuan tiap kategori.'],
                        ]; @endphp
                        @foreach($faqs as [$q, $a])
                        <div class="space-y-2">
                            <h4 class="text-sm font-black text-on-surface leading-tight flex items-start gap-2">
                                <span class="material-symbols-outlined text-primary text-base mt-0.5">help</span>
                                {{ $q }}
                            </h4>
                            <p class="text-sm text-on-surface-variant font-medium leading-relaxed pl-6">{{ $a }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Final CTA -->
            <div class="p-10 md:p-16 rounded-[48px] bg-primary text-on-primary flex flex-col md:flex-row items-center justify-between gap-10 overflow-hidden relative shadow-premium-lg">
                <div class="z-10 relative">
                    <h4 class="text-3xl font-black tracking-tight mb-4 leading-tight">Siap Melangkah Lebih Jauh?</h4>
                    <p class="text-primary-container text-lg font-medium opacity-80">
                        Mulailah perjalanan riset Anda dengan mendaftarkan akun mahasiswa sekarang juga.
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
                <div class="absolute right-0 top-0 w-80 h-80 bg-white/10 rounded-full -mr-20 -mt-20 blur-[100px]"></div>
            </div>

        </div>
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

</body>
</html>
