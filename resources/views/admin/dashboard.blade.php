@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Dashboard</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Pusat Kendali</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Pantau aktivitas akademik, verifikasi pendaftaran, dan status pengarsipan digital secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.archive') }}" class="flex items-center gap-2 px-5 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all hover:bg-surface-container-highest border border-outline-variant/10">
                <span class="material-symbols-outlined text-[20px]">file_download</span> Ekspor Arsip
            </a>
        </div>
    </header>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    @foreach([
        [
            'label' => 'Total Mahasiswa',
            'value' => \App\Models\User::where('role', 'mahasiswa')->count(),
            'icon' => 'group',
            'color' => 'bg-primary',
            'sub' => 'Terdaftar'
        ],
        [
            'label' => 'Akun Menunggu',
            'value' => \App\Models\User::where('role', 'mahasiswa')->where('status', 'pending')->count(),
            'icon' => 'pending_actions',
            'color' => 'bg-warning',
            'sub' => 'Perlu Verifikasi',
            'link' => route('admin.mahasiswa.pending')
        ],
        [
            'label' => 'Skripsi Selesai',
            'value' => \App\Models\Thesis::where('status', 'finished')->count(),
            'icon' => 'verified',
            'color' => 'bg-success',
            'sub' => 'Terverifikasi'
        ],
        [
            'label' => 'Menunggu Verif',
            'value' => \App\Models\Thesis::withoutGlobalScope(\App\Models\Scopes\ThreeYearScope::class)->where('status', 'uploaded')->count(),
            'icon' => 'quick_reference_all',
            'color' => 'bg-accent-indigo',
            'sub' => 'Dokumen Baru',
            'link' => route('admin.theses.index')
        ]
    ] as $stat)
    <div class="premium-card p-6 relative overflow-hidden group {{ isset($stat['link']) ? 'cursor-pointer' : '' }}" 
         @isset($stat['link']) onclick="window.location.href='{{ $stat['link'] }}'" @endisset>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 {{ $stat['color'] }} rounded-2xl flex items-center justify-center text-white shadow-lg shadow-current/30 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">{{ $stat['icon'] }}</span>
                </div>
                <span class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-widest">{{ $stat['sub'] }}</span>
            </div>
            <h3 class="text-xs font-black uppercase tracking-[0.15em] text-on-surface-variant mb-1">{{ $stat['label'] }}</h3>
            <div class="text-4xl font-black text-on-surface tracking-tighter">{{ $stat['value'] }}</div>
        </div>
        <!-- Decorative element -->
        <div class="absolute -right-4 -bottom-4 w-24 h-24 {{ $stat['color'] }} opacity-[0.03] rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Statistik Pengajuan Judul -->
    <div class="lg:col-span-2 premium-card p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
            <div>
                <h2 class="text-xl font-black text-on-surface tracking-tight">Statistik Pengajuan Judul</h2>
                <p class="text-on-surface-variant text-sm font-medium">Status usulan judul penelitian mahasiswa.</p>
            </div>
            <a href="{{ route('admin.title-proposals.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface text-xs font-bold rounded-xl hover:bg-surface-container-highest transition-colors">
                Kelola Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/10 text-center">
                <div class="text-3xl font-black text-warning tracking-tighter mb-1">{{ \App\Models\TitleProposal::where('status', 'pending')->count() }}</div>
                <div class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Menunggu</div>
            </div>
            <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/10 text-center">
                <div class="text-3xl font-black text-success tracking-tighter mb-1">{{ \App\Models\TitleProposal::where('status', 'approved')->count() }}</div>
                <div class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Disetujui</div>
            </div>
            <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/10 text-center">
                <div class="text-3xl font-black text-error tracking-tighter mb-1">{{ \App\Models\TitleProposal::where('status', 'rejected')->count() }}</div>
                <div class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Ditolak</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('admin.title-proposals.index') }}" class="flex items-center gap-4 p-5 bg-primary/5 border border-primary/10 rounded-2xl hover:bg-primary/10 transition-all group">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">fact_check</span>
                </div>
                <div>
                    <p class="text-sm font-black text-on-surface uppercase tracking-tight">Konfirmasi Judul</p>
                    <p class="text-xs text-on-surface-variant font-medium">Tinjau usulan baru</p>
                </div>
            </a>
            <a href="{{ route('admin.dosen.index') }}" class="flex items-center gap-4 p-5 bg-surface-container-high rounded-2xl border border-outline-variant/10 hover:bg-surface-container-highest transition-all group">
                <div class="w-12 h-12 bg-surface-container-highest rounded-xl flex items-center justify-center text-on-surface group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">school</span>
                </div>
                <div>
                    <p class="text-sm font-black text-on-surface uppercase tracking-tight">Kelola Dosen</p>
                    <p class="text-xs text-on-surface-variant font-medium">{{ \App\Models\Dosen::count() }} Dosen Aktif</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="premium-card p-8 flex flex-col cursor-pointer group hover:bg-surface-container-lowest transition-all" 
         onclick="window.location.href='{{ route('admin.activity.index') }}'">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-black text-on-surface tracking-tight group-hover:text-primary transition-colors">Aktivitas</h2>
            <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] bg-surface-container-high px-2 py-1 rounded group-hover:bg-primary group-hover:text-on-primary transition-all">Lihat Log</span>
        </div>
        
        <div class="space-y-6 flex-grow">
            @forelse(auth()->user()->notifications()->take(5)->get() as $notif)
            <div class="flex gap-4 relative">
                @if(!$loop->last)
                <div class="absolute left-5 top-10 bottom-[-24px] w-px bg-outline-variant/20"></div>
                @endif
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm
                    @if(($notif->data['type'] ?? '') === 'success') bg-success/10 text-success
                    @elseif(($notif->data['type'] ?? '') === 'error') bg-error/10 text-error
                    @else bg-primary/10 text-primary
                    @endif">
                    <span class="material-symbols-outlined text-[18px]">notifications_active</span>
                </div>
                <div class="flex-1 min-w-0 pt-1">
                    <p class="text-xs font-bold text-on-surface leading-snug line-clamp-2">{{ $notif->data['message'] }}</p>
                    <p class="text-[10px] text-on-surface-variant font-bold uppercase mt-2 tracking-wider">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center mb-4 text-outline-variant">
                    <span class="material-symbols-outlined text-4xl">history</span>
                </div>
                <p class="text-xs text-on-surface-variant font-bold uppercase tracking-widest">Belum ada aktivitas</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

