@extends('layouts.app')

@section('title', 'NIM Whitelist')

@section('content')
<div class="space-y-8" x-data="{ search: '' }">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">NIM Whitelist</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Otorisasi NIM</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Kelola daftar NIM mahasiswa yang diizinkan mendaftar ke sistem secara manual atau melalui import massal.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari NIM/Nama..." 
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-10">

        <!-- Kolom Kiri: Tabel -->
        <div class="lg:col-span-2">
            <div class="premium-card overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low">
                    <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Data Whitelist</h3>
                    <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">{{ $whitelists->total() }} Entri</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                                <th class="px-6 py-4 text-left">Identitas NIM</th>
                                <th class="px-6 py-4">Nama Mahasiswa</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            @forelse($whitelists as $item)
                            <tr x-show="'{{ strtolower($item->nim) }} {{ strtolower($item->name ?? '') }}'.includes(search.toLowerCase())"
                                class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="px-6 py-5">
                                    <span class="px-2.5 py-1 bg-surface-container-high text-on-surface font-mono font-black rounded-lg text-[11px] tracking-widest border border-outline-variant/10">
                                        {{ $item->nim }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-on-surface">{{ $item->name ?: '-' }}</td>
                                <td class="px-6 py-5">
                                    @if($item->is_used)
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-full border border-primary/20 uppercase tracking-widest">
                                            Terpakai
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-black rounded-full border border-outline-variant/10 uppercase tracking-widest">
                                            Tersedia
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    @if(!$item->is_used)
                                    <form action="{{ route('admin.whitelist.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus NIM {{ $item->nim }} dari whitelist?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-error/5 text-error hover:bg-error hover:text-white transition-all shadow-sm ml-auto">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-xs text-on-surface-variant opacity-20 italic">No Action</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4 border border-outline-variant/5">
                                            <span class="material-symbols-outlined text-4xl">list_alt</span>
                                        </div>
                                        <p class="text-on-surface-variant font-black">Whitelist Kosong</p>
                                        <p class="text-[11px] text-on-surface-variant opacity-50 mt-1 uppercase tracking-widest">Belum ada NIM yang didaftarkan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($whitelists->hasPages())
                <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/10">
                    {{ $whitelists->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Form Tambah + Import -->
        <div class="lg:col-span-1 space-y-6" x-data="{ tab: 'manual' }">

            <!-- Tab Switch -->
            <div class="flex bg-surface-container-low rounded-2xl p-1.5 border border-outline-variant/10 shadow-inner">
                <button @click="tab = 'manual'" type="button"
                        :class="tab === 'manual' ? 'bg-white shadow-sm text-primary font-black' : 'text-on-surface-variant font-bold opacity-60'"
                        class="flex-1 py-2.5 text-[11px] uppercase tracking-widest rounded-xl transition-all">
                    Manual
                </button>
                <button @click="tab = 'import'" type="button"
                        :class="tab === 'import' ? 'bg-white shadow-sm text-primary font-black' : 'text-on-surface-variant font-bold opacity-60'"
                        class="flex-1 py-2.5 text-[11px] uppercase tracking-widest rounded-xl transition-all">
                    Import Massal
                </button>
            </div>

            <!-- Tab: Tambah Manual -->
            <section x-show="tab === 'manual'" class="premium-card p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">add_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface tracking-tighter">Tambah NIM</h3>
                        <p class="text-xs text-on-surface-variant font-medium">Input identitas tunggal</p>
                    </div>
                </div>

                <form action="{{ route('admin.whitelist.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nomor Induk (NIM)</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" required
                               placeholder="Contoh: 2021110001"
                               class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold font-mono tracking-widest focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nama <span class="opacity-40 font-bold lowercase">(opsional)</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama lengkap..."
                               class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <button type="submit"
                            class="w-full py-4 bg-primary text-on-primary rounded-[20px] font-black text-sm shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">add</span> Tambahkan
                    </button>
                </form>
            </section>

            <!-- Tab: Import File -->
            <section x-show="tab === 'import'" x-cloak class="premium-card p-8 space-y-6">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-outlined text-2xl font-variation-settings:'FILL' 1;">upload_file</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface tracking-tighter">Import Data</h3>
                        <p class="text-xs text-on-surface-variant font-medium">Gunakan file CSV atau XLSX</p>
                    </div>
                </div>

                <div class="bg-surface-container-low rounded-2xl p-5 space-y-4 border border-outline-variant/5">
                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">Panduan Kolom File</p>
                    <div class="rounded-xl overflow-hidden border border-outline-variant/10 text-[10px] font-bold">
                        <table class="w-full">
                            <thead class="bg-surface-container-high">
                                <tr>
                                    <th class="px-3 py-2 text-left text-on-surface">nim</th>
                                    <th class="px-3 py-2 text-left text-on-surface">name</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="border-t border-outline-variant/5"><td class="px-3 py-2 font-mono">2021110001</td><td class="px-3 py-2 text-on-surface-variant">Budi Santoso</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('admin.whitelist.template') }}"
                       class="w-full py-2.5 border border-primary/10 text-primary rounded-xl font-black text-[10px] flex items-center justify-center gap-2 hover:bg-primary/5 transition-all uppercase tracking-widest">
                        <span class="material-symbols-outlined text-sm">download</span> Template CSV
                    </a>
                </div>

                <form action="{{ route('admin.whitelist.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div x-data="{ fileName: '' }">
                        <div class="relative border-2 border-dashed border-outline-variant/20 rounded-[24px] p-8 text-center hover:border-primary transition-all cursor-pointer bg-surface-container-lowest"
                             onclick="document.getElementById('file-input').click()">
                            <span class="material-symbols-outlined text-on-surface-variant/20 text-4xl mb-2">cloud_upload</span>
                            <p class="text-[11px] font-black text-on-surface uppercase tracking-widest" x-text="fileName || 'Klik untuk pilih file'"></p>
                            <p class="text-[9px] text-on-surface-variant font-bold mt-1 opacity-50">CSV, XLS, XLSX — Maks 2MB</p>
                            <input type="file" name="file" id="file-input" required accept=".csv,.xls,.xlsx"
                                   class="hidden" @change="fileName = $event.target.files[0]?.name ?? ''">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full py-4 bg-primary text-on-primary rounded-[20px] font-black text-sm shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined">cloud_upload</span> Mulai Import
                    </button>
                </form>
            </section>

        </div>
    </div>
</div>
@endsection
