@extends('layouts.app')

@section('title', 'Akses Angkatan')

@section('content')
<div class="space-y-8" x-data="{ search: '' }">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Akses Angkatan</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Kontrol Akses</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Buka atau tutup akses pendaftaran mahasiswa berdasarkan tahun angkatan (NIM). Mahasiswa hanya dapat mendaftar jika angkatannya berstatus <span class="text-primary font-bold">Terbuka</span>.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari angkatan..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64">
            </div>
        </div>
    </header>

    @if(session('status'))
        <div class="p-5 bg-success/5 text-success border border-success/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-5 bg-error/5 text-error border border-error/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-lg">error</span> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start pb-10">

        <!-- Form Tambah Angkatan -->
        <div class="lg:col-span-2">
            <div class="premium-card p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">add_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface tracking-tighter">Buka Angkatan</h3>
                        <p class="text-xs text-on-surface-variant font-medium">Tambah otorisasi pendaftaran</p>
                    </div>
                </div>

                <form action="{{ route('admin.angkatan.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest block px-1">Tahun Angkatan</label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                               min="2000" max="2099" required
                               class="w-full px-6 py-6 bg-surface-container-low border-transparent rounded-[24px] text-4xl font-black text-center tracking-[0.1em] text-primary focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none shadow-inner">
                        <p class="text-[10px] text-on-surface-variant font-bold text-center opacity-50 uppercase tracking-widest">Gunakan 4 digit tahun masuk</p>
                    </div>

                    <div class="bg-primary/5 rounded-2xl p-5 border border-primary/10">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">info</span>
                            <p class="text-[11px] text-on-surface-variant leading-relaxed font-bold">
                                NIM mahasiswa akan divalidasi berdasarkan 4 digit pertama. Contoh: NIM <span class="text-primary font-black">2024</span>XXXXXX hanya bisa daftar jika angkatan <span class="text-primary font-black">2024</span> terbuka.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-4 bg-primary text-on-primary rounded-[20px] font-black text-sm shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">lock_open</span>
                        Aktifkan Angkatan
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Angkatan -->
        <div class="lg:col-span-3 space-y-4">
            <h3 class="text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] px-2 mb-4">Angkatan Terdaftar</h3>
            
            @forelse($angkatans as $angkatan)
            <div x-show="'{{ $angkatan->year }}'.includes(search)" 
                 class="premium-card px-6 py-5 flex items-center justify-between group hover:bg-surface-container-low transition-all">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl {{ $angkatan->is_open ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'bg-surface-container-high text-on-surface-variant/40' }} flex items-center justify-center font-black text-xl transition-all duration-500">
                        {{ substr($angkatan->year, 2) }}
                    </div>
                    <div>
                        <p class="font-black text-on-surface text-lg tracking-tight">Angkatan {{ $angkatan->year }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $angkatan->is_open ? 'bg-success animate-pulse' : 'bg-on-surface-variant/30' }}"></span>
                            <span class="text-[10px] text-on-surface-variant font-black uppercase tracking-widest opacity-60">
                                {{ $angkatan->is_open ? 'Akses Terbuka' : 'Akses Terkunci' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Toggle Buka/Tutup -->
                    <form action="{{ route('admin.angkatan.toggle', $angkatan->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border
                                       {{ $angkatan->is_open
                                            ? 'bg-success/5 text-success border-success/10 hover:bg-success hover:text-white'
                                            : 'bg-surface-container-high text-on-surface-variant border-transparent hover:bg-on-surface-variant hover:text-white' }}">
                            <span class="material-symbols-outlined text-sm font-black">{{ $angkatan->is_open ? 'lock_open' : 'lock' }}</span>
                            {{ $angkatan->is_open ? 'Terbuka' : 'Terkunci' }}
                        </button>
                    </form>

                    <!-- Hapus -->
                    <form action="{{ route('admin.angkatan.destroy', $angkatan->id) }}" method="POST"
                          onsubmit="return confirm('Hapus angkatan {{ $angkatan->year }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-10 h-10 flex items-center justify-center rounded-xl text-error bg-error/5 border border-error/5 hover:bg-error hover:text-white transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="premium-card p-20 text-center flex flex-col items-center">
                <div class="w-20 h-20 bg-surface-container-high rounded-[32px] flex items-center justify-center text-on-surface-variant/20 mb-6 border border-outline-variant/5">
                    <span class="material-symbols-outlined text-5xl">event_busy</span>
                </div>
                <p class="text-on-surface font-black text-lg">Belum ada angkatan terdaftar</p>
                <p class="text-[11px] text-on-surface-variant font-bold mt-2 uppercase tracking-widest opacity-50">Tambahkan tahun untuk membuka akses pendaftaran.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
