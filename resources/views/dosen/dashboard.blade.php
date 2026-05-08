@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')

<div class="space-y-8">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Pembimbing</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Dashboard</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Dashboard Akademik</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Selamat datang, <strong class="text-primary font-black">{{ $dosen->nama_gelar ?? auth()->user()->name }}</strong>. Pantau progress bimbingan dan tinjau berkas mahasiswa Anda.</p>
        </div>
    </header>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <div class="premium-card p-8 flex flex-col gap-4 group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] opacity-60">Total Bimbingan</span>
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-lg shadow-primary/5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">group</span>
                </div>
            </div>
            <div class="flex items-end justify-between mt-2">
                <span class="text-5xl font-black text-on-surface tracking-tighter">{{ $totalBimbingan }}</span>
                <span class="text-[10px] font-black text-primary bg-primary/5 px-3 py-1.5 rounded-full uppercase tracking-widest border border-primary/10">Mahasiswa</span>
            </div>
        </div>

        <div class="premium-card p-8 flex flex-col gap-4 group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] opacity-60">Perlu Review</span>
                <div class="w-12 h-12 bg-warning/10 rounded-2xl flex items-center justify-center text-warning shadow-lg shadow-warning/5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">pending_actions</span>
                </div>
            </div>
            <div class="flex items-end justify-between mt-2">
                <span class="text-5xl font-black text-on-surface tracking-tighter">{{ $needReview }}</span>
                <span class="text-[10px] font-black text-warning bg-warning/5 px-3 py-1.5 rounded-full uppercase tracking-widest border border-warning/10">Tindakan</span>
            </div>
        </div>

        <div class="premium-card p-8 flex flex-col gap-4 group">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] opacity-60">Sudah Direview</span>
                <div class="w-12 h-12 bg-success/10 rounded-2xl flex items-center justify-center text-success shadow-lg shadow-success/5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">task_alt</span>
                </div>
            </div>
            <div class="flex items-end justify-between mt-2">
                <span class="text-5xl font-black text-on-surface tracking-tighter">{{ $doneReview }}</span>
                <span class="text-[10px] font-black text-success bg-success/5 px-3 py-1.5 rounded-full uppercase tracking-widest border border-success/10">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="premium-card overflow-hidden">
        <div class="px-8 py-6 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low">
            <div>
                <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">history</span>
                    Aktivitas Bimbingan Terbaru
                </h3>
            </div>
            <a href="{{ route('dosen.theses.index') }}" class="px-5 py-2.5 bg-surface-container-high text-on-surface text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-surface-container-highest transition-all flex items-center gap-2 border border-outline-variant/10 shadow-sm">
                Lihat Semua
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-8 py-4">Mahasiswa</th>
                        <th class="px-8 py-4">Judul Penelitian</th>
                        <th class="px-8 py-4 text-center">Tugas</th>
                        <th class="px-8 py-4">Status & Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($theses as $thesis)
                    @php
                        $supervisor = \App\Models\ThesisSupervisor::where('thesis_id', $thesis->id)->where('dosen_id', $dosen->id)->first();
                        $progress = match($thesis->status) {
                            'pending'         => 10,
                            'approved'        => 50,
                            'uploaded'        => 85,
                            'finished'        => 100,
                            default           => 0,
                        };
                        $statusLabel = match($thesis->status) {
                            'pending'         => ['label' => 'Menunggu Plotting', 'color' => 'bg-warning/10 text-warning border-warning/20'],
                            'approved'        => ['label' => 'Bimbingan Aktif', 'color' => 'bg-primary/10 text-primary border-primary/20'],
                            'uploaded'        => ['label' => 'Siap Verifikasi', 'color' => 'bg-accent-indigo/10 text-accent-indigo border-accent-indigo/20'],
                            'finished'        => ['label' => 'Selesai', 'color' => 'bg-success/10 text-success border-success/20'],
                            default           => ['label' => strtoupper($thesis->status), 'color' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/10'],
                        };
                    @endphp
                    <tr class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-black text-xs shadow-sm">
                                    {{ strtoupper(substr($thesis->mahasiswa->user->name ?? 'M', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-on-surface">{{ $thesis->mahasiswa->user->name ?? '-' }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-bold tracking-widest">{{ $thesis->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-bold text-on-surface leading-snug max-w-[320px] line-clamp-2" title="{{ $thesis->title }}">{{ $thesis->title }}</p>
                            <p class="text-[10px] text-on-surface-variant font-bold mt-2 opacity-50 uppercase tracking-widest">{{ $thesis->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 bg-surface-container-high text-on-surface text-[10px] font-black rounded-lg uppercase tracking-widest border border-outline-variant/10 shadow-sm">
                                Pembimbing {{ $supervisor->type ?? '?' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusLabel['color'] }} w-fit">
                                    {{ $statusLabel['label'] }}
                                </span>
                                <div class="flex items-center gap-4">
                                    <div class="flex-grow h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-1000 group-hover:brightness-110" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-on-surface tracking-tighter shrink-0">{{ $progress }}%</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-surface-container rounded-[32px] flex items-center justify-center text-outline-variant mb-6">
                                    <span class="material-symbols-outlined text-5xl">clinical_notes</span>
                                </div>
                                <h4 class="text-on-surface font-black text-xl tracking-tight mb-2">Belum Ada Aktivitas</h4>
                                <p class="text-[11px] text-on-surface-variant font-black uppercase tracking-widest opacity-50">Mahasiswa bimbingan Anda akan tampil di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
