@extends('layouts.app')

@section('title', 'Export Arsip')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Arsip & Ekspor</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Pusat Arsip</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Kelola dokumentasi digital dan ekspor basis data skripsi ke format Excel untuk keperluan pelaporan institusi.</p>
        </div>
    </header>

    <!-- Archive Stats Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
        <div class="premium-card p-10 flex flex-col items-center justify-center text-center space-y-6">
            <div class="w-20 h-20 bg-success/10 rounded-[28px] flex items-center justify-center text-success border border-success/10 shadow-sm">
                <span class="material-symbols-outlined text-4xl font-variation-settings:'FILL' 1;">cloud_download</span>
            </div>
            <div>
                <h3 class="text-5xl font-black text-on-surface tracking-tighter">{{ $totalFinished }}</h3>
                <p class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mt-2 opacity-60">Total Skripsi Selesai</p>
            </div>
            <p class="text-xs text-on-surface-variant max-w-xs leading-relaxed font-medium">
                Seluruh data skripsi dengan status <span class="text-success font-black uppercase">Selesai</span> siap untuk diunduh sebagai arsip digital permanen.
            </p>
            <a href="{{ route('admin.archive.export') }}" class="w-full py-5 bg-primary text-on-primary rounded-[24px] font-black shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-xs hover:-translate-y-1">
                <span class="material-symbols-outlined">grid_on</span> Export ke Excel (.xlsx)
            </a>
        </div>

        <div class="bg-primary/5 rounded-[40px] p-10 border border-primary/10 flex flex-col items-center justify-center text-center space-y-6">
            <div class="w-20 h-20 bg-primary/10 rounded-[28px] flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                <span class="material-symbols-outlined text-4xl">public</span>
            </div>
            <div>
                <h3 class="text-5xl font-black text-primary tracking-tighter">{{ $totalPublic }}</h3>
                <p class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mt-2 opacity-60">Data Publik Aktif</p>
            </div>
            <p class="text-xs text-on-surface-variant max-w-xs leading-relaxed font-medium">
                Jumlah skripsi yang saat ini dapat diakses melalui portal publik (filter otomatis 3 tahun terakhir).
            </p>
            <div class="pt-4 flex items-center gap-2 text-[10px] font-black text-primary uppercase tracking-widest bg-white/50 px-4 py-2 rounded-full border border-primary/5">
                <span class="material-symbols-outlined text-sm">verified_user</span>
                Sistem Arsip Terenkripsi
            </div>
        </div>
    </div>

    <!-- Security & Info -->
    <div class="premium-card p-8 bg-surface-container-low/50">
        <h4 class="text-xs font-black text-on-surface mb-6 flex items-center gap-3 uppercase tracking-widest">
            <span class="material-symbols-outlined text-primary text-xl">shield</span>
            Protokol Keamanan Data
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0 font-black text-sm">01</div>
                <p class="text-[11px] text-on-surface-variant leading-relaxed font-bold">Data ekspor mencakup identitas mahasiswa, judul, jurnal, dan dospem secara lengkap.</p>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0 font-black text-sm">02</div>
                <p class="text-[11px] text-on-surface-variant leading-relaxed font-bold">Hanya administrator tingkat tinggi yang memiliki otorisasi untuk melakukan pengunduhan arsip.</p>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0 font-black text-sm">03</div>
                <p class="text-[11px] text-on-surface-variant leading-relaxed font-bold">Pastikan integritas data telah diperiksa melalui modul verifikasi sebelum penutupan tahun akademik.</p>
            </div>
        </div>
    </div>
</div>
@endsection
