@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- Page Header --}}
    <div>
        <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-100 border border-blue-200 px-2.5 py-1 rounded-full uppercase tracking-wider">
                <span class="material-symbols-outlined" style="font-size:13px">person</span>
                Mahasiswa
            </span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Profil & Akun</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    {{-- Status Notifikasi --}}
    @if(session('status'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700">
        <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
        {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <span class="material-symbols-outlined mt-0.5" style="font-size:18px">error</span>
        <ul class="space-y-0.5 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kartu Identitas Kiri --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
                {{-- Avatar Besar --}}
                <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-extrabold text-3xl mx-auto mb-4 border-4 border-white shadow-md ring-2 ring-indigo-100">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>

                <h2 class="text-lg font-bold text-slate-800 leading-tight">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400 mt-0.5">{{ $user->email }}</p>

                <div class="mt-4 space-y-2 text-left">
                    {{-- NIM --}}
                    <div class="flex items-center gap-2 bg-slate-50 rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined text-slate-400" style="font-size:16px">badge</span>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">NIM</p>
                            <p class="text-sm font-bold text-slate-700 font-mono">{{ $mahasiswa->nim ?? '-' }}</p>
                        </div>
                    </div>
                    {{-- Status Akun --}}
                    <div class="flex items-center gap-2 bg-slate-50 rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined text-slate-400" style="font-size:16px">verified_user</span>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Status Akun</p>
                            @if($user->status === 'active')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> {{ ucfirst($user->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    {{-- Terdaftar Sejak --}}
                    <div class="flex items-center gap-2 bg-slate-50 rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined text-slate-400" style="font-size:16px">calendar_today</span>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Terdaftar Sejak</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Edit Profil Kanan --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Form Informasi Pribadi --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-500" style="font-size:18px">manage_accounts</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-400">Perbarui nama dan nomor kontak Anda</p>
                    </div>
                </div>

                <form action="{{ route('mahasiswa.profile.update') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 transition-all">
                    </div>

                    {{-- Username (read-only) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Username Login</label>
                        <input type="text" value="{{ $user->username }}" disabled
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-100 text-sm font-mono text-slate-400 cursor-not-allowed">
                        <p class="text-xs text-slate-400 mt-1">Username tidak dapat diubah.</p>
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Nomor Telepon / HP</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <span class="material-symbols-outlined" style="font-size:16px">phone</span>
                            </span>
                            <input type="text" name="phone" value="{{ old('phone', $mahasiswa->phone ?? '') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-300 transition-all">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200">
                            <span class="material-symbols-outlined" style="font-size:16px">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Ganti Password --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-400" style="font-size:18px">lock</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Keamanan Akun</h3>
                        <p class="text-xs text-slate-400">Perbarui password untuk menjaga keamanan akun</p>
                    </div>
                </div>

                <form action="{{ route('mahasiswa.profile.update') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Isi name & phone agar tidak ke-reset saat submit form password --}}
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="phone" value="{{ $mahasiswa->phone ?? '' }}">

                    <div x-data="{ show: false }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="Minimal 8 karakter"
                                   class="w-full px-4 py-2.5 pr-12 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition-all">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <div x-data="{ show: false }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Ulangi password baru"
                                   class="w-full px-4 py-2.5 pr-12 rounded-xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-300 transition-all">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-xl" x-text="show ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-500 text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-all shadow-md shadow-red-100">
                            <span class="material-symbols-outlined" style="font-size:16px">lock_reset</span>
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
