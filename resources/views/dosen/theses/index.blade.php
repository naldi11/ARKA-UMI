@extends('layouts.app')

@section('title', 'Daftar Bimbingan')

@section('content')
<div class="space-y-8" x-data="{ search: '' }">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Dosen Area</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Manajemen Bimbingan</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Bimbingan Akademik</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Monitoring progress penelitian mahasiswa aktif dan kelola tugas peninjauan usulan judul serta dokumen skripsi.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari bimbingan..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64">
            </div>
        </div>
    </header>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="premium-card p-6 flex flex-col gap-2 relative overflow-hidden group">
            <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] opacity-60">Total Bimbingan</span>
            <div class="flex items-end justify-between">
                <span class="text-4xl font-black text-primary tracking-tight">{{ $theses->count() }}</span>
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">groups</span>
                </div>
            </div>
        </div>
        <div class="premium-card p-6 flex flex-col gap-2 relative overflow-hidden group">
            <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] opacity-60">Menunggu Review</span>
            <div class="flex items-end justify-between">
                @php $pendingReview = $theses->whereIn('status', ['pending', 'dosen1_approved'])->count(); @endphp
                <span class="text-4xl font-black text-warning tracking-tight">{{ $pendingReview }}</span>
                <div class="w-10 h-10 bg-warning/10 rounded-xl flex items-center justify-center text-warning">
                    <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">pending_actions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="premium-card overflow-hidden">
        <div class="px-8 py-6 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low">
            <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Daftar Mahasiswa Bimbingan</h3>
            <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">Status Penelitian Real-time</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-8 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Judul & Jurnal</th>
                        <th class="px-6 py-4 text-center">Progress Berkas</th>
                        <th class="px-6 py-4">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($theses as $thesis)
                        @php
                            $supervisor = $thesis->supervisors()->where('dosen_id', $dosen->id)->first();
                            $uploaded = $thesis->uploadedCount();
                            $progress = ($uploaded / 8) * 100;
                        @endphp
                        <tr x-show="'{{ strtolower($thesis->mahasiswa->user->name) }} {{ strtolower($thesis->mahasiswa->nim) }} {{ strtolower($thesis->title) }}'.includes(search.toLowerCase())"
                            class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-accent-indigo text-white flex items-center justify-center font-black text-xs shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($thesis->mahasiswa->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-on-surface group-hover:text-primary transition-colors">{{ $thesis->mahasiswa->user->name }}</div>
                                        <div class="text-[10px] text-on-surface-variant font-bold tracking-widest">{{ $thesis->mahasiswa->nim }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6 max-w-[300px]">
                                <div class="text-sm font-bold text-on-surface line-clamp-2 leading-snug" title="{{ $thesis->title }}">{{ $thesis->title }}</div>
                                <div class="inline-flex items-center gap-1.5 mt-2 px-2 py-0.5 bg-surface-container-high rounded text-[9px] text-primary font-black uppercase tracking-widest border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-[12px]">auto_stories</span>
                                    {{ $thesis->jurnal_name }}
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-on-surface-variant">
                                        <span>Berkas</span>
                                        <span class="{{ $uploaded == 8 ? 'text-success' : 'text-primary' }}">{{ $uploaded }}/8</span>
                                    </div>
                                    <div class="w-24 h-1.5 bg-surface-container-high rounded-full overflow-hidden mx-auto border border-outline-variant/5">
                                        <div class="h-full bg-primary transition-all duration-1000" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                @if($thesis->status === 'finished')
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-success/10 text-success border border-success/20">
                                            <span class="material-symbols-outlined text-[14px]">verified</span> Lulus
                                        </span>
                                        <span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest ml-1">{{ $thesis->approved_at ? \Carbon\Carbon::parse($thesis->approved_at)->format('d/m/Y') : '' }}</span>
                                    </div>
                                @elseif($thesis->status === 'uploaded')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-accent-indigo/10 text-accent-indigo border border-accent-indigo/20">
                                        <span class="material-symbols-outlined text-[14px]">pending</span> Verifikasi Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-primary/10 text-primary border border-primary/20">
                                        <span class="material-symbols-outlined text-[14px]">groups</span> Masa Bimbingan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4">
                                        <span class="material-symbols-outlined text-4xl">person_off</span>
                                    </div>
                                    <h4 class="text-on-surface font-black text-xl tracking-tight mb-1">Belum Ada Bimbingan</h4>
                                    <p class="text-[11px] text-on-surface-variant font-black uppercase tracking-widest opacity-50">Mahasiswa bimbingan akan tampil di sini.</p>
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
