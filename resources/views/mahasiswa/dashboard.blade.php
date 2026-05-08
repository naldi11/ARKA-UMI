@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')

@php
    $mahasiswa = Auth::user()->mahasiswa;
    $nim = $mahasiswa->nim ?? '-';

    // Status mapping untuk stepper
    $statusMap = [
        'pending'         => 1,
        'approved'        => 2,
        'uploaded'        => 3,
        'finished'        => 4,
    ];
    $currentStep = $thesis ? ($statusMap[$thesis->status] ?? 0) : 0;

    $steps = [
        ['label' => 'Judul & Dospem',       'icon' => 'verified_user'],
        ['label' => 'Bimbingan & Upload',  'icon' => 'groups'],
        ['label' => 'Verifikasi Admin',    'icon' => 'fact_check'],
        ['label' => 'Selesai & Arsip',     'icon' => 'inventory_2'],
    ];

    $pendingCount   = $proposals->where('status', 'pending')->count();
    $approvedCount  = $proposals->where('status', 'approved')->count();
    $rejectedCount  = $proposals->where('status', 'rejected')->count();
    $totalProposals = $proposals->count();
@endphp

<!-- Header Section -->
<div class="mb-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                Portal Mahasiswa
            </div>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface">Halo, {{ Auth::user()->name }}</h1>
            <p class="text-on-surface-variant font-medium mt-1">NIM: <span class="font-bold text-on-surface">{{ $nim }}</span> • Program Studi Akuntansi</p>
        </div>
        <div class="flex gap-3">
            <div class="px-5 py-2.5 bg-surface-container-high rounded-2xl border border-outline-variant/10 text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">event</span>
                {{ date('d M Y') }}
            </div>
        </div>
    </div>
</div>

<!-- Stepper Progress -->
@if($thesis)
<div class="premium-card p-8 mb-10 overflow-hidden relative">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 relative z-10">
        <div>
            <h3 class="text-xl font-black text-on-surface tracking-tight">Progress Skripsi</h3>
            <p class="text-sm text-on-surface-variant font-medium mt-1">Status saat ini: <span class="text-primary font-black uppercase tracking-wider">{{ str_replace('_', ' ', $thesis->status) }}</span></p>
        </div>
        <div class="px-4 py-2 {{ $thesis->status === 'finished' ? 'bg-success/10 text-success' : ($thesis->status === 'uploaded' ? 'bg-accent-indigo/10 text-accent-indigo' : 'bg-primary/10 text-primary') }} rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2">
            <span class="material-symbols-outlined text-sm font-variation-settings: 'FILL' 1;">{{ $thesis->status === 'finished' ? 'task_alt' : ($thesis->status === 'uploaded' ? 'verified_user' : 'hourglass_top') }}</span>
            @if($thesis->status === 'approved')
                {{ $thesis->uploadedCount() }}/8 Dokumen Terunggah
            @else
                {{ $thesis->status === 'finished' ? 'Selesai' : 'Verifikasi Admin' }}
            @endif
        </div>
    </div>
    
    <!-- Track Stepper -->
    <div class="relative flex items-start justify-between z-10 px-4 min-w-[600px] lg:min-w-0 overflow-x-auto pb-4">
        <!-- Progress Line Background -->
        <div class="absolute top-6 left-12 right-12 h-1 bg-surface-container-high rounded-full"></div>
        <!-- Progress Line Active -->
        @php $width = (($currentStep - 1) / (count($steps) - 1)) * 100; @endphp
        <div class="absolute top-6 left-12 h-1 bg-primary rounded-full transition-all duration-1000 ease-out" style="width: calc({{ $width }}% - 24px)"></div>

        @foreach($steps as $i => $step)
        @php
            $step_num = $i + 1;
            $isCompleted = $currentStep > $step_num || ($step_num === 4 && $thesis->status === 'finished');
            $isActive    = $currentStep === $step_num && $thesis->status !== 'finished';
        @endphp
        <div class="flex flex-col items-center gap-4 relative w-24">
            @if($isCompleted)
            <div class="w-12 h-12 rounded-2xl bg-success flex items-center justify-center text-white shadow-lg shadow-success/30 ring-4 ring-white">
                <span class="material-symbols-outlined text-2xl font-variation-settings: 'FILL' 1;">check</span>
            </div>
            @elseif($isActive)
            <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/30 ring-4 ring-white animate-bounce">
                <span class="material-symbols-outlined text-2xl">{{ $step['icon'] }}</span>
            </div>
            @else
            <div class="w-12 h-12 rounded-2xl bg-surface-container-high border-2 border-outline-variant/20 flex items-center justify-center text-on-surface-variant/40 ring-4 ring-white">
                <span class="material-symbols-outlined text-2xl">{{ $step['icon'] }}</span>
            </div>
            @endif
            <span class="text-[10px] font-black text-center leading-tight uppercase tracking-widest {{ $isActive ? 'text-primary' : ($isCompleted ? 'text-success' : 'text-on-surface-variant/50') }}">
                {{ $step['label'] }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Kolom Kiri -->
    <div class="lg:col-span-2 space-y-8">

        @if(!$thesis)
        <!-- BELUM ADA SKRIPSI: Tampilkan Form Pengajuan Judul -->
        <div class="premium-card p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl">add_task</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-on-surface tracking-tight">Ajukan Judul Skripsi</h3>
                    <p class="text-sm text-on-surface-variant font-medium">Anda dapat mengajukan maksimal 5 opsi judul penelitian.</p>
                </div>
            </div>

            @if($totalProposals < 5 && $approvedCount === 0)
            <form action="{{ route('mahasiswa.proposal.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Judul Penelitian</label>
                    <textarea name="title" rows="4" required placeholder="Tuliskan judul penelitian Anda secara lengkap dan jelas..."
                        class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/30 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Target Jurnal <span class="text-on-surface-variant/50 font-medium lowercase">(opsional)</span></label>
                    <input type="text" name="jurnal_name" placeholder="Contoh: Jurnal Kajian Akuntansi (JKA)"
                        class="w-full px-5 py-4 bg-surface-container-low rounded-2xl border border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 text-on-surface font-bold placeholder:text-on-surface-variant/30 transition-all outline-none">
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-outline-variant/10">
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Sisa Kuota: <span class="text-primary">{{ 5 - $totalProposals }}</span></p>
                    <button type="submit" class="btn-primary w-full sm:w-auto">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
            @elseif($approvedCount > 0)
            <div class="p-6 bg-success/5 border border-success/10 rounded-2xl flex items-center gap-4">
                <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success shrink-0">
                    <span class="material-symbols-outlined font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <p class="text-sm font-bold text-success">Salah satu judul telah disetujui! Silakan hubungi Dospem untuk bimbingan.</p>
            </div>
            @else
            <div class="p-6 bg-warning/5 border border-warning/10 rounded-2xl flex items-center gap-4">
                <div class="w-10 h-10 bg-warning/10 rounded-xl flex items-center justify-center text-warning shrink-0">
                    <span class="material-symbols-outlined">info</span>
                </div>
                <p class="text-sm font-bold text-warning">Kuota pengajuan judul (5/5) telah penuh. Silakan tunggu keputusan Admin.</p>
            </div>
            @endif
        </div>

        <!-- Daftar Judul Diajukan -->
        @if($proposals->count() > 0)
        <div class="space-y-4">
            <h3 class="text-lg font-black text-on-surface tracking-tight px-1">Judul yang Diajukan</h3>
            <div class="grid grid-cols-1 gap-4">
                @foreach($proposals as $i => $proposal)
                <div class="premium-card p-6 border-l-4 {{ $proposal->status === 'approved' ? 'border-success' : ($proposal->status === 'rejected' ? 'border-error' : 'border-warning') }}">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-2.5 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-black uppercase tracking-widest rounded-lg">#{{ $i + 1 }}</span>
                                @if($proposal->status === 'pending')
                                <span class="text-[10px] font-black text-warning uppercase tracking-widest">Menunggu</span>
                                @elseif($proposal->status === 'approved')
                                <span class="text-[10px] font-black text-success uppercase tracking-widest">Disetujui</span>
                                @else
                                <span class="text-[10px] font-black text-error uppercase tracking-widest">Ditolak</span>
                                @endif
                            </div>
                            <h4 class="text-base font-black text-on-surface leading-snug">{{ $proposal->title }}</h4>
                            @if($proposal->jurnal_name)
                            <p class="text-xs font-bold text-primary mt-2 uppercase tracking-tight">Jurnal: {{ $proposal->jurnal_name }}</p>
                            @endif
                            @if($proposal->rejection_reason)
                            <div class="mt-4 p-3 bg-error/5 rounded-xl text-xs text-error font-medium italic">
                                "{{ $proposal->rejection_reason }}"
                            </div>
                            @endif
                        </div>
                        @if($proposal->status === 'pending')
                        <form action="{{ route('mahasiswa.proposal.delete', $proposal) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center text-on-surface-variant/40 hover:text-error hover:bg-error/5 rounded-xl transition-all">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @else
        <!-- SUDAH ADA THESIS -->
        <div class="premium-card p-8">
            <div class="flex items-center justify-between mb-8 pb-6 border-b border-outline-variant/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-2xl font-variation-settings: 'FILL' 1;">description</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-on-surface tracking-tight">Informasi Penelitian</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Data skripsi terintegrasi sistem.</p>
                    </div>
                </div>
                <div class="px-3 py-1.5 bg-surface-container-high rounded-xl text-[10px] font-black text-on-surface tracking-widest uppercase">
                    Ref: {{ date('Y') }}-{{ $thesis->id }}
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Judul Skripsi Disetujui</p>
                    <h4 class="text-lg font-black text-on-surface leading-relaxed tracking-tight">{{ $thesis->title }}</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($thesis->supervisors as $sup)
                    <div class="p-5 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-base">school</span>
                            </div>
                            <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Dospem {{ $sup->type }}</span>
                        </div>
                        <p class="text-sm font-black text-on-surface">{{ $sup->dosen->nama_gelar ?? 'Belum Ditentukan' }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Dokumen Download -->
                @if($thesis->doc_skripsi || $thesis->doc_meja_hijau || $thesis->doc_jurnal || $thesis->doc_cd || $thesis->doc_target_jurnal || $thesis->doc_sk_pembimbing_1 || $thesis->doc_sk_pembimbing_2 || $thesis->doc_izin_penelitian)
                <div class="pt-6 border-t border-outline-variant/10">
                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-4">Arsip Dokumen Terupload</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @if($thesis->doc_jurnal)
                        <a href="{{ $thesis->getDownloadUrl('jurnal') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-primary/5 rounded-2xl border border-primary/10 hover:bg-primary/10 transition-all text-primary">
                            <span class="material-symbols-outlined">article</span>
                            <span class="text-[10px] font-black uppercase">Jurnal</span>
                        </a>
                        @endif
                        @if($thesis->doc_target_jurnal)
                        <a href="{{ $thesis->getDownloadUrl('target_jurnal') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 hover:bg-indigo-100 transition-all text-indigo-700">
                            <span class="material-symbols-outlined">description</span>
                            <span class="text-[10px] font-black uppercase">T. Jurnal</span>
                        </a>
                        @endif
                        @if($thesis->doc_sk_pembimbing_1)
                        <a href="{{ $thesis->getDownloadUrl('sk_pembimbing_1') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-2xl border border-blue-100 hover:bg-blue-100 transition-all text-blue-700">
                            <span class="material-symbols-outlined">assignment_ind</span>
                            <span class="text-[10px] font-black uppercase">SK 1</span>
                        </a>
                        @endif
                        @if($thesis->doc_sk_pembimbing_2)
                        <a href="{{ $thesis->getDownloadUrl('sk_pembimbing_2') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-2xl border border-blue-100 hover:bg-blue-100 transition-all text-blue-700">
                            <span class="material-symbols-outlined">assignment_ind</span>
                            <span class="text-[10px] font-black uppercase">SK 2</span>
                        </a>
                        @endif
                        @if($thesis->doc_izin_penelitian)
                        <a href="{{ $thesis->getDownloadUrl('izin_penelitian') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-amber-50 rounded-2xl border border-amber-100 hover:bg-amber-100 transition-all text-amber-700">
                            <span class="material-symbols-outlined">badge</span>
                            <span class="text-[10px] font-black uppercase">Izin</span>
                        </a>
                        @endif
                        @if($thesis->doc_skripsi)
                        <a href="{{ $thesis->getDownloadUrl('skripsi') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-accent-indigo/5 rounded-2xl border border-accent-indigo/10 hover:bg-accent-indigo/10 transition-all text-accent-indigo">
                            <span class="material-symbols-outlined">picture_as_pdf</span>
                            <span class="text-[10px] font-black uppercase">Skripsi</span>
                        </a>
                        @endif
                        @if($thesis->doc_meja_hijau)
                        <a href="{{ $thesis->getDownloadUrl('meja_hijau') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-accent-emerald/5 rounded-2xl border border-accent-emerald/10 hover:bg-accent-emerald/10 transition-all text-accent-emerald">
                            <span class="material-symbols-outlined">verified</span>
                            <span class="text-[10px] font-black uppercase">Meja Hijau</span>
                        </a>
                        @endif
                        @if($thesis->doc_cd)
                        <a href="{{ $thesis->getDownloadUrl('cd') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-on-surface/5 rounded-2xl border border-outline-variant/10 hover:bg-on-surface/10 transition-all text-on-surface">
                            <span class="material-symbols-outlined">album</span>
                            <span class="text-[10px] font-black uppercase">CD</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Catatan Review -->
        @php $dosenNotes = $thesis->supervisors->filter(fn($s) => $s->review_notes); @endphp
        @if($dosenNotes->count() > 0)
        <div class="premium-card p-8 bg-warning/5 border-warning/10">
            <h3 class="text-lg font-black text-on-surface tracking-tight mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined text-warning">rate_review</span>
                Catatan Pembimbing
            </h3>
            <div class="space-y-4">
                @foreach($dosenNotes as $sup)
                <div class="p-6 bg-white rounded-2xl border border-warning/20 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div class="text-xs font-black text-on-surface uppercase">{{ $sup->dosen->nama_gelar }}</div>
                        <div class="text-[10px] font-bold text-on-surface-variant uppercase">{{ \Carbon\Carbon::parse($sup->reviewed_at)->diffForHumans() }}</div>
                    </div>
                    <p class="text-sm text-on-surface-variant font-medium italic relative z-10">"{{ $sup->review_notes }}"</p>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.05] text-warning">
                        <span class="material-symbols-outlined text-7xl">format_quote</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif

    </div>

    <!-- Kolom Kanan -->
    <div class="space-y-8">
        <!-- Akun Card -->
        <div class="auth-gradient rounded-[32px] p-8 text-white relative overflow-hidden shadow-premium-lg">
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-6 border border-white/20 backdrop-blur-md">
                    <span class="material-symbols-outlined text-2xl font-variation-settings: 'FILL' 1;">account_circle</span>
                </div>
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-primary-fixed-dim mb-1">Identitas Digital</h3>
                <p class="text-2xl font-black tracking-tight mb-6">{{ Auth::user()->name }}</p>
                
                <div class="space-y-4">
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-[9px] font-black text-primary-fixed-dim uppercase tracking-widest mb-1">Nomor Induk Mahasiswa</p>
                        <p class="font-mono font-black text-lg tracking-widest">{{ $nim }}</p>
                    </div>
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                        <p class="text-[9px] font-black text-primary-fixed-dim uppercase tracking-widest mb-1">Email Institusi</p>
                        <p class="text-xs font-bold truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Shortcut Upload -->
        @if($thesis && in_array($thesis->status, ['approved', 'uploaded']))
        <div class="premium-card p-6">
            <h3 class="text-xs font-black text-on-surface uppercase tracking-[0.2em] mb-6 px-1">Tahapan Unggah Berkas</h3>
                @php
                    $vData = $thesis->verification_data ?? [];
                    
                    // Status Stage 1
                    $s1Fields = ['sk_pembimbing_1', 'sk_pembimbing_2', 'target_jurnal', 'izin_penelitian'];
                    $s1Statuses = array_map(fn($f) => $vData[$f]['status'] ?? ($thesis->{"doc_$f"} ? 'pending' : 'empty'), $s1Fields);
                    $s1AllAccepted = !in_array('empty', $s1Statuses) && !in_array('pending', $s1Statuses) && !in_array('rejected', $s1Statuses);
                    $s1AnyRejected = in_array('rejected', $s1Statuses);
                    $s1AnyPending = in_array('pending', $s1Statuses);
                    
                    $s1Color = $s1AllAccepted ? 'bg-success/5 text-success border-success/10' : ($s1AnyRejected ? 'bg-error/5 text-error border-error/10' : ($s1AnyPending ? 'bg-warning/5 text-warning border-warning/10' : 'bg-surface-container-low text-on-surface border-transparent'));
                    $s1Icon = $s1AllAccepted ? 'verified' : ($s1AnyRejected ? 'report' : ($s1AnyPending ? 'hourglass_top' : 'assignment_ind'));

                    // Status Stage 2
                    $s2Fields = ['jurnal', 'skripsi'];
                    $s2Statuses = array_map(fn($f) => $vData[$f]['status'] ?? ($thesis->{"doc_$f"} ? 'pending' : 'empty'), $s2Fields);
                    $s2AllAccepted = !in_array('empty', $s2Statuses) && !in_array('pending', $s2Statuses) && !in_array('rejected', $s2Statuses);
                    $s2AnyRejected = in_array('rejected', $s2Statuses);
                    $s2AnyPending = in_array('pending', $s2Statuses);
                    $s1Done = $thesis->isBatch1Complete();

                    $s2Color = !$s1Done ? 'opacity-40 cursor-not-allowed bg-surface-container-lowest border-transparent' : ($s2AllAccepted ? 'bg-success/5 text-success border-success/10' : ($s2AnyRejected ? 'bg-error/5 text-error border-error/10' : ($s2AnyPending ? 'bg-warning/5 text-warning border-warning/10' : 'bg-surface-container-low text-on-surface border-transparent')));
                    $s2Icon = !$s1Done ? 'lock' : ($s2AllAccepted ? 'verified' : ($s2AnyRejected ? 'report' : ($s2AnyPending ? 'hourglass_top' : 'article')));

                    // Status Stage 3
                    $s3Fields = ['meja_hijau', 'cd'];
                    $s3Statuses = array_map(fn($f) => $vData[$f]['status'] ?? ($thesis->{"doc_$f"} ? 'pending' : 'empty'), $s3Fields);
                    $s3AllAccepted = !in_array('empty', $s3Statuses) && !in_array('pending', $s3Statuses) && !in_array('rejected', $s3Statuses);
                    $s3AnyRejected = in_array('rejected', $s3Statuses);
                    $s3AnyPending = in_array('pending', $s3Statuses);
                    $s2Done = $thesis->doc_jurnal && $thesis->doc_skripsi;

                    $s3Color = !$s2Done ? 'opacity-40 cursor-not-allowed bg-surface-container-lowest border-transparent' : ($s3AllAccepted ? 'bg-success/5 text-success border-success/10' : ($s3AnyRejected ? 'bg-error/5 text-error border-error/10' : ($s3AnyPending ? 'bg-warning/5 text-warning border-warning/10' : 'bg-surface-container-low text-on-surface border-transparent')));
                    $s3Icon = !$s2Done ? 'lock' : ($s3AllAccepted ? 'verified' : ($s3AnyRejected ? 'report' : ($s3AnyPending ? 'hourglass_top' : 'verified')));
                @endphp

                <!-- Batch 1 -->
                <a href="{{ route('mahasiswa.upload.administrasi.page') }}" class="flex items-center justify-between p-5 rounded-3xl group transition-all border {{ $s1Color }} hover:shadow-lg">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white/50 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">{{ $s1Icon }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Tahap 1</p>
                            <p class="text-xs font-black uppercase tracking-tight">Administrasi</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
                </a>

                <!-- Batch 2 -->
                <a href="{{ $s1Done ? route('mahasiswa.upload.penelitian.page') : '#' }}" class="flex items-center justify-between p-5 rounded-3xl group transition-all border {{ $s2Color }} {{ $s1Done ? 'hover:shadow-lg' : '' }}">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white/50 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">{{ $s2Icon }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Tahap 2</p>
                            <p class="text-xs font-black uppercase tracking-tight">Penelitian</p>
                        </div>
                    </div>
                    @if($s1Done)
                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
                    @endif
                </a>

                <!-- Batch 3 -->
                <a href="{{ $s2Done ? route('mahasiswa.upload.kelulusan.page') : '#' }}" class="flex items-center justify-between p-5 rounded-3xl group transition-all border {{ $s3Color }} {{ $s2Done ? 'hover:shadow-lg' : '' }}">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-white/50 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">{{ $s3Icon }}</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50">Tahap 3</p>
                            <p class="text-xs font-black uppercase tracking-tight">Kelulusan</p>
                        </div>
                    </div>
                    @if($s2Done)
                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
                    @endif
                </a>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        @if(!$thesis && $proposals->count() > 0)
        <div class="premium-card p-8">
            <h3 class="text-sm font-black text-on-surface uppercase tracking-widest mb-6">Ringkasan Judul</h3>
            <div class="space-y-5">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-on-surface-variant uppercase">Total Diajukan</span>
                    <span class="text-sm font-black text-on-surface">{{ $totalProposals }}/5</span>
                </div>
                <div class="w-full bg-surface-container-high h-1.5 rounded-full overflow-hidden">
                    <div class="bg-primary h-full transition-all duration-500" style="width: {{ ($totalProposals/5)*100 }}%"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-success/5 rounded-2xl text-center">
                        <p class="text-lg font-black text-success">{{ $approvedCount }}</p>
                        <p class="text-[9px] font-black text-success/70 uppercase">Diterima</p>
                    </div>
                    <div class="p-4 bg-warning/5 rounded-2xl text-center">
                        <p class="text-lg font-black text-warning">{{ $pendingCount }}</p>
                        <p class="text-[9px] font-black text-warning/70 uppercase">Antre</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

