@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="space-y-8 max-w-3xl mx-auto">
    <section class="space-y-1">
        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Application Settings</h2>
        <p class="text-slate-500 text-sm font-medium">Konfigurasi parameter operasional portal skripsi.</p>
    </section>

    <!-- Form Pengaturan Domain -->
    <section class="bg-white rounded-xl p-8 shadow-sm border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-700">domain</span>
            Domain Email Institusi
        </h3>
        
        <p class="text-xs text-slate-500 leading-relaxed mb-6">
            Domain ini digunakan untuk validasi registrasi jalur Universitas. Mahasiswa yang mendaftar menggunakan email ini akan diwajibkan menginput NIM.
        </p>

        @if(session('status'))
            <div class="mb-6 text-xs text-emerald-700 font-bold px-4 py-3 bg-emerald-100 rounded-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 text-xs text-red-600 font-bold px-4 py-3 bg-red-100 rounded-lg">
                @foreach($errors->all() as $err) <span>{{ $err }}</span><br> @endforeach
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 block" for="allowed_email_domain">Domain yang Diizinkan</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 font-bold text-lg">@</span>
                    <input type="text" name="allowed_email_domain" required value="{{ old('allowed_email_domain', $domain->value ?? '') }}" placeholder="mahasiswa.univ.ac.id"
                           class="block w-full pl-10 pr-4 py-4 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500/20 text-sm font-medium transition-all text-blue-700">
                </div>
                <p class="text-[10px] text-slate-400 mt-2 italic">*Contoh format: @student.kampus.ac.id</p>
            </div>
            
            <button type="submit" class="w-full py-4 bg-blue-900 text-white rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:shadow-xl hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span> Simpan Perubahan
            </button>
        </form>
    </section>
</div>
@endsection
