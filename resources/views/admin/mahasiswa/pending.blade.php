@extends('layouts.app')

@section('title', 'Verifikasi Mahasiswa')

@section('content')
<div class="space-y-8" x-data="{ search: '' }">
    <!-- Page Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Manajemen</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary">Registrasi Akun</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Verifikasi Pendaftaran</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Tinjau antrean pendaftaran mahasiswa baru sebelum memberikan hak akses ke dalam sistem.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari mahasiswa..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64">
            </div>
            <button class="flex items-center gap-2 px-5 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all hover:bg-surface-container-highest border border-outline-variant/10">
                <span class="material-symbols-outlined text-[20px]">filter_list</span> Filter
            </button>
            <button class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl font-black text-sm shadow-premium hover:shadow-premium-lg transition-all hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[20px]">download</span> Unduh Daftar
            </button>
        </div>
    </header>

    <!-- Table Section -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/10">
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Mahasiswa</th>
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Identitas</th>
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5 text-sm">
                    @forelse($users as $index => $user)
                    <tr x-show="'{{ strtolower($user->name) }} {{ strtolower($user->mahasiswa->nim ?? '') }} {{ strtolower($user->username) }}'.includes(search.toLowerCase())"
                        class="group hover:bg-surface-container-lowest transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-black text-xs shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-black text-on-surface text-sm">{{ $user->name }}</p>
                                    <p class="text-[11px] text-on-surface-variant font-bold uppercase tracking-wider">@ {{ $user->username }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-on-surface-variant font-bold">{{ $user->email }}</td>
                        <td class="px-6 py-5">
                            <span class="px-2.5 py-1 bg-surface-container-high text-on-surface font-black rounded-lg text-[11px] tracking-widest border border-outline-variant/10">
                                {{ $user->mahasiswa->nim ?? '--' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-on-surface-variant font-bold">{{ $user->created_at->format('d/m/Y') }} <span class="text-[10px] opacity-50 ml-1">{{ $user->created_at->format('H:i') }}</span></td>
                        <td class="px-6 py-5">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-warning/10 text-warning text-[10px] font-black rounded-full border border-warning/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-warning animate-pulse"></span>
                                {{ strtoupper($user->status) }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 transition-all">
                                <form action="{{ route('admin.mahasiswa.approve', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-success text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-success/20 hover:bg-success/90 transition-all hover:-translate-y-0.5">
                                        Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.mahasiswa.reject', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-surface-container-high text-error text-[11px] font-black uppercase tracking-widest rounded-xl border border-error/10 hover:bg-error hover:text-white transition-all hover:-translate-y-0.5">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4">
                                    <span class="material-symbols-outlined text-4xl">how_to_reg</span>
                                </div>
                                <p class="text-on-surface-variant font-black">Tidak ada antrean pendaftaran.</p>
                                <p class="text-[11px] text-on-surface-variant opacity-50 mt-1 uppercase tracking-widest">Semua pengajuan telah diproses.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
