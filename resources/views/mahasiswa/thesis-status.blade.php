@extends('layouts.app')

@section('title', 'Status Skripsi')

@section('content')
<div class="space-y-10">

@if(!$thesis)
{{-- ============ EMPTY STATE ============ --}}
<div class="relative p-20 bg-white rounded-[48px] shadow-premium-sm border border-outline-variant/10 overflow-hidden text-center group">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
    <div class="relative z-10">
        <div class="w-24 h-24 bg-surface-container-low rounded-[32px] flex items-center justify-center mx-auto mb-8 border border-outline-variant/5">
            <span class="material-symbols-outlined text-outline-variant/30 text-5xl">description</span>
        </div>
        <h2 class="text-3xl font-black text-on-surface tracking-tighter mb-3">Belum Ada Skripsi Aktif</h2>
        <p class="text-on-surface-variant font-medium mb-10 max-w-sm mx-auto leading-relaxed">Ajukan judul skripsi pertama Anda melalui halaman dashboard untuk memulai proses akademik.</p>
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="inline-flex items-center gap-3 px-10 py-5 bg-on-surface text-white text-sm font-black rounded-3xl hover:bg-primary transition-all shadow-xl shadow-on-surface/20">
            <span class="material-symbols-outlined">home</span> Ke Dashboard Utama
        </a>
    </div>
</div>

@else
@php
    $statusMap = [
        'pending'         => ['step' => 1, 'label' => 'Menunggu Plotting',    'color' => 'from-slate-500 to-slate-700',     'icon' => 'pending'],
        'approved'        => ['step' => 2, 'label' => 'Masa Bimbingan',       'color' => 'from-primary to-accent-indigo',    'icon' => 'edit_document'],
        'uploaded'        => ['step' => 3, 'label' => 'Verifikasi Digital',    'color' => 'from-accent-indigo to-primary',   'icon' => 'fact_check'],
        'finished'        => ['step' => 4, 'label' => 'Selesai & Lulus',      'color' => 'from-success to-emerald-600',     'icon' => 'verified'],
    ];
    $cfg = $statusMap[$thesis->status] ?? $statusMap['pending'];
    $currentStep = $cfg['step'];
@endphp

{{-- ============ HERO CARD ============ --}}
<div class="relative p-12 bg-gradient-to-br {{ $cfg['color'] }} rounded-[48px] text-white shadow-2xl shadow-primary/20 overflow-hidden group">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
    <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-black/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
        <div class="flex-1 min-w-0">
            <nav class="flex items-center gap-2 text-[10px] font-black text-white/50 mb-4 uppercase tracking-[0.2em]">
                <span>Status Akademik</span>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-white">Progres Skripsi</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter leading-tight mb-4 max-w-3xl">{{ $thesis->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-4">
                <div class="px-4 py-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">auto_stories</span>
                    <span class="text-[11px] font-black uppercase tracking-widest">{{ $thesis->jurnal_name }}</span>
                </div>
                <div class="px-4 py-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">event</span>
                    <span class="text-[11px] font-black uppercase tracking-widest">{{ $thesis->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="shrink-0 flex flex-col items-center lg:items-end gap-3">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-xl rounded-[32px] border border-white/20 flex items-center justify-center shadow-inner">
                <span class="material-symbols-outlined text-5xl">{{ $cfg['icon'] }}</span>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Status Saat Ini</p>
                <p class="text-xl font-black tracking-tight">{{ $cfg['label'] }}</p>
            </div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="mt-12 relative">
        <div class="flex justify-between items-end mb-4">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-60">Langkah Terlewati</p>
                <p class="text-lg font-black">{{ $currentStep }} <span class="opacity-40">/ 4</span></p>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ round(($currentStep/4)*100) }}% Selesai</p>
        </div>
        <div class="h-3 bg-black/10 rounded-full overflow-hidden border border-white/5">
            <div class="h-full bg-white shadow-[0_0_15px_rgba(255,255,255,0.5)] transition-all duration-1000 ease-out" style="width: {{ ($currentStep/4)*100 }}%"></div>
        </div>
    </div>
</div>

{{-- ============ BODY CONTENT ============ --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
    <!-- Left Column: Details & Supervisors -->
    <div class="lg:col-span-3 space-y-8">
        
        <!-- Admin Feedback (If Any) -->
        @if($thesis->admin_notes)
        <div class="relative p-8 bg-error text-white rounded-[40px] shadow-xl shadow-error/20 overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex gap-6">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center shrink-0 border border-white/20">
                    <span class="material-symbols-outlined text-3xl">gavel</span>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-2">Catatan Verifikator</h4>
                    <p class="text-sm font-bold leading-relaxed">"{{ $thesis->admin_notes }}"</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Supervisors Section -->
        <div class="premium-card p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-on-surface tracking-tight">Dosen Pembimbing</h3>
                    <p class="text-xs text-on-surface-variant font-medium">Tim pendamping akademik Anda</p>
                </div>
                <div class="w-12 h-12 bg-primary/5 text-primary rounded-2xl flex items-center justify-center border border-primary/5">
                    <span class="material-symbols-outlined">school</span>
                </div>
            </div>

            @if($thesis->supervisors->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($thesis->supervisors as $supervisor)
                <div class="group p-6 bg-surface-container-low rounded-3xl border border-outline-variant/10 hover:bg-white hover:border-primary/30 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary font-black text-xl shadow-sm border border-outline-variant/10 group-hover:scale-110 transition-transform">
                            {{ strtoupper(substr($supervisor->dosen->nama_gelar, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-on-surface group-hover:text-primary transition-colors">{{ $supervisor->dosen->nama_gelar }}</p>
                            <p class="text-[10px] font-bold text-on-surface-variant/60 mt-1 uppercase tracking-widest">NIP. {{ $supervisor->dosen->nip }}</p>
                        </div>
                    </div>
                    <div class="mt-5 pt-5 border-t border-outline-variant/5 flex items-center justify-between">
                        <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 bg-primary/5 text-primary rounded-lg border border-primary/10">Pembimbing {{ $supervisor->type }}</span>
                        <span class="text-[9px] font-bold text-on-surface-variant">Aktif</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center bg-surface-container-lowest rounded-[32px] border border-dashed border-outline-variant/20">
                <span class="material-symbols-outlined text-5xl text-outline-variant/30 mb-4">person_search</span>
                <p class="text-sm font-black text-on-surface-variant uppercase tracking-widest">Menunggu Plotting Admin</p>
            </div>
            @endif
        </div>

        <!-- Documents Checklist (Read Only) -->
        <div class="premium-card overflow-hidden">
            <div class="px-10 py-8 border-b border-outline-variant/10 bg-surface-container-low flex items-center justify-between">
                <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Kelengkapan Berkas Digital</h3>
                <span class="text-[11px] font-black text-primary uppercase tracking-widest">{{ $thesis->uploadedCount() }}/8 Dokumen</span>
            </div>
            <div class="divide-y divide-outline-variant/5">
                @php
                    $docs = [
                        ['key' => 'sk_pembimbing_1',   'label' => 'SK Pembimbing 1',       'icon' => 'assignment_ind'],
                        ['key' => 'sk_pembimbing_2',   'label' => 'SK Pembimbing 2',       'icon' => 'assignment_ind'],
                        ['key' => 'target_jurnal',     'label' => 'Judul & Target Jurnal', 'icon' => 'description'],
                        ['key' => 'izin_penelitian',   'label' => 'Surat Izin Penelitian', 'icon' => 'badge'],
                        ['key' => 'jurnal',            'label' => 'Jurnal Skripsi',         'icon' => 'article'],
                        ['key' => 'skripsi',           'label' => 'Dokumen Skripsi (PDF)', 'icon' => 'picture_as_pdf'],
                        ['key' => 'meja_hijau',        'label' => 'Dokumen Meja Hijau',    'icon' => 'verified'],
                        ['key' => 'cd',                'label' => 'CD Skripsi (Zip/Rar)',  'icon' => 'album'],
                    ];
                @endphp
                @foreach($docs as $doc)
                @php
                    $key = $doc['key'];
                    $field = 'doc_' . $key;
                    $path = $thesis->$field;
                    $vData = ($thesis->verification_data ?? [])[$key] ?? null;
                    $status = $vData['status'] ?? ($path ? 'pending' : 'none');
                @endphp
                <div class="px-10 py-5 flex items-center justify-between hover:bg-surface-container-lowest transition-colors group">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $path ? 'bg-primary/5 text-primary' : 'bg-slate-50 text-slate-300' }} border border-outline-variant/10 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">{{ $doc['icon'] }}</span>
                        </div>
                        <span class="text-xs font-bold text-on-surface">{{ $doc['label'] }}</span>
                    </div>
                    @if($status === 'accepted')
                        <span class="material-symbols-outlined text-success">check_circle</span>
                    @elseif($status === 'rejected')
                        <span class="material-symbols-outlined text-error">cancel</span>
                    @elseif($status === 'pending')
                        <span class="material-symbols-outlined text-warning animate-pulse">hourglass_top</span>
                    @else
                        <div class="w-2 h-2 rounded-full bg-slate-200"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Stepper -->
    <div class="lg:col-span-2">
        <div class="premium-card p-10 sticky top-24">
            <h3 class="text-xl font-black text-on-surface tracking-tight mb-2">Alur Proses</h3>
            <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-8">{{ $currentStep }} dari 4 langkah selesai</p>

            <div class="space-y-10 relative">
                {{-- Vertical Line --}}
                <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-surface-container-high rounded-full"></div>

                @foreach([
                    ['label' => 'Pengajuan Judul',    'desc' => 'Judul diajukan ke sistem', 'icon' => 'edit_note'],
                    ['label' => 'Penugasan Dospem',   'desc' => 'Admin menentukan pembimbing', 'icon' => 'assignment_ind'],
                    ['label' => 'Berkas & Validasi',  'desc' => 'Unggah berkas & cek Admin', 'icon' => 'upload_file'],
                    ['label' => 'Yudisium & Selesai', 'desc' => 'Pernyataan lulus & arsip', 'icon' => 'military_tech'],
                ] as $i => $step)
                @php
                    $isDone = ($i + 1) < $currentStep || ($i + 1 === 4 && $thesis->status === 'finished');
                    $isActive = ($i + 1) === $currentStep && $thesis->status !== 'finished';
                @endphp
                <div class="relative flex gap-6 group">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border-2 transition-all duration-500 z-10
                        @if($isDone) bg-success border-success text-white shadow-lg shadow-success/20
                        @elseif($isActive) bg-primary border-primary text-white shadow-lg shadow-primary/20 scale-110
                        @else bg-white border-outline-variant/20 text-on-surface-variant/30
                        @endif">
                        <span class="material-symbols-outlined text-2xl">{{ $isDone ? 'check' : $step['icon'] }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-tight {{ $isActive ? 'text-primary' : ($isDone ? 'text-on-surface' : 'text-on-surface-variant/40') }}">
                            {{ $step['label'] }}
                        </h4>
                        <p class="text-[10px] font-bold text-on-surface-variant/60 mt-1 uppercase tracking-widest">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            @if($thesis->status === 'approved')
            <div class="mt-12 pt-8 border-t border-outline-variant/10">
                <a href="{{ route('mahasiswa.upload.administrasi.page') }}" class="w-full py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-xl shadow-primary/20 hover:shadow-primary-lg transition-all active:scale-95">
                    Lanjut Unggah Berkas <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
</div>
@endsection
