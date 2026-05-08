@extends('layouts.app')

@section('title', $thesis->title)

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    {{-- Back Link --}}
    <div class="mb-10 animate-in fade-in slide-in-from-left-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] hover:text-primary transition-colors group">
            <span class="material-symbols-outlined text-[16px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Katalog
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="premium-card p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                
                <div class="flex items-center gap-4 mb-8">
                    <span class="px-4 py-1.5 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full border border-primary/10">
                        Terarsip Digital
                    </span>
                    <span class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest opacity-50">
                        Disetujui: {{ \Carbon\Carbon::parse($thesis->approved_at)->format('d M Y') }}
                    </span>
                </div>

                <h1 class="text-3xl md:text-4xl font-black text-on-surface leading-[1.1] mb-10 tracking-tighter">
                    {{ $thesis->title }}
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-y border-outline-variant/10">
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-black uppercase tracking-widest mb-3 opacity-40">Penulis / Mahasiswa</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-surface-container-high text-on-surface-variant flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($thesis->mahasiswa->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-base font-black text-on-surface leading-tight">{{ $thesis->mahasiswa->user->name }}</p>
                                <p class="text-[11px] text-on-surface-variant font-bold tracking-widest mt-1 uppercase">{{ $thesis->mahasiswa->nim }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] text-on-surface-variant font-black uppercase tracking-widest mb-3 opacity-40">Target Publikasi</p>
                        <div class="flex items-center gap-3 text-on-surface">
                            <span class="material-symbols-outlined text-primary">auto_stories</span>
                            <p class="text-sm font-bold italic">{{ $thesis->jurnal_name ?? 'Nasional / Internasional' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Download Actions --}}
                <div class="mt-10 p-8 bg-surface-container-low rounded-[24px] border border-outline-variant/10">
                    <h3 class="text-sm font-black text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">cloud_download</span>
                        Akses Dokumen Publik
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('thesis.download', ['thesis' => $thesis->id, 'type' => 'jurnal']) }}" target="_blank"
                           class="flex items-center justify-between p-5 bg-white border border-outline-variant/10 rounded-2xl hover:border-primary hover:shadow-lg transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/5 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-on-surface uppercase tracking-widest">File Jurnal</p>
                                    <p class="text-[10px] text-on-surface-variant font-medium">PDF Document</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all">chevron_right</span>
                        </a>
                        
                        <a href="{{ route('thesis.download', ['thesis' => $thesis->id, 'type' => 'skripsi']) }}" target="_blank"
                           class="flex items-center justify-between p-5 bg-white border border-outline-variant/10 rounded-2xl hover:border-accent-indigo hover:shadow-lg transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-accent-indigo/5 text-accent-indigo flex items-center justify-center group-hover:bg-accent-indigo group-hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-[20px]">description</span>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-on-surface uppercase tracking-widest">Full Skripsi</p>
                                    <p class="text-[10px] text-on-surface-variant font-medium">Verified PDF</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            {{-- Supervisors --}}
            <div class="premium-card p-8">
                <h3 class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-6 opacity-60">Tim Pembimbing</h3>
                <div class="space-y-4">
                    @foreach($thesis->supervisors as $sv)
                    <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/5">
                        <div class="w-10 h-10 rounded-xl bg-white border border-outline-variant/10 flex items-center justify-center text-[10px] font-black text-primary">
                            {{ $sv->type == 1 ? 'D1' : 'D2' }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-on-surface leading-tight">{{ $sv->dosen->nama_gelar }}</p>
                            <p class="text-[10px] text-on-surface-variant font-bold opacity-50 mt-0.5">{{ $sv->dosen->nip }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- System Metadata --}}
            <div class="premium-card p-8 bg-surface-container-low">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between py-2 border-b border-outline-variant/5">
                        <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">ID Arsip</span>
                        <span class="text-[10px] font-mono font-bold text-on-surface opacity-60">#{{ str_pad($thesis->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-outline-variant/5">
                        <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</span>
                        <span class="px-2 py-0.5 bg-success/10 text-success text-[9px] font-black uppercase tracking-widest rounded-full border border-success/10">Verifikasi</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Program Studi</span>
                        <span class="text-[10px] font-bold text-on-surface opacity-60 uppercase">Akuntansi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
