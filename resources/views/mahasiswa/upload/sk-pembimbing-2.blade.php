@extends('layouts.app')

@section('title', 'Upload SK Pembimbing 2')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-7">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2 uppercase tracking-widest font-semibold">
            <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-slate-600 transition">Dashboard</a>
            <span class="material-symbols-outlined text-[12px]">chevron_right</span>
            <span class="text-blue-700 font-bold">Upload SK Pembimbing 2</span>
        </nav>
        <h1 class="text-2xl font-black tracking-tight text-slate-900">Upload SK Pembimbing 2</h1>
        <p class="text-slate-500 text-sm mt-1">Unggah file SK Pembimbing 2 Anda (format PDF, maks. 10 MB).</p>
    </div>

    @if(!$thesis)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 flex items-start gap-3">
        <span class="material-symbols-outlined text-yellow-600 text-xl mt-0.5">warning</span>
        <div>
            <p class="text-sm font-bold text-yellow-800">Skripsi tidak ditemukan</p>
            <p class="text-xs text-yellow-700 mt-1">Anda belum memiliki skripsi aktif untuk mengunggah dokumen ini.</p>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 p-7">
        <div class="flex items-center gap-3 mb-6 pb-5 border-b border-slate-100">
            <div class="w-10 h-10 {{ $thesis->doc_sk_pembimbing_2 ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined {{ $thesis->doc_sk_pembimbing_2 ? 'text-emerald-600' : 'text-slate-500' }} text-[22px]" style="{{ $thesis->doc_sk_pembimbing_2 ? 'font-variation-settings:\'FILL\' 1' : '' }}">
                    {{ $thesis->doc_sk_pembimbing_2 ? 'check_circle' : 'assignment_ind' }}
                </span>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">SK Pembimbing 2</h3>
                <p class="text-xs {{ $thesis->doc_sk_pembimbing_2 ? 'text-emerald-600' : 'text-slate-400' }}">
                    {{ $thesis->doc_sk_pembimbing_2 ? 'Sudah diunggah — dapat diganti dengan file baru' : 'Belum diunggah' }}
                </p>
            </div>
        </div>

        <form action="{{ route('mahasiswa.upload.sk-pembimbing-2') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div x-data="{ fileName: '' }" class="relative">
                <label class="block text-xs font-semibold text-slate-600 mb-2">File SK Pembimbing 2 (PDF)</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-slate-400 hover:bg-slate-50 transition cursor-pointer relative">
                    <input type="file" name="doc_sk_pembimbing_2" accept=".pdf" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                    <span class="material-symbols-outlined text-3xl text-slate-300 mb-2">assignment_ind</span>
                    <p class="text-sm font-medium text-slate-500">Klik atau seret file ke sini</p>
                    <p class="text-xs text-slate-400 mt-1" x-show="!fileName">Format: PDF — maksimal 10 MB</p>
                    <p class="text-xs text-slate-700 font-semibold mt-1" x-show="fileName" x-text="fileName"></p>
                </div>
            </div>
            <button type="submit" class="w-full py-3.5 bg-blue-900 text-white text-sm font-bold rounded-xl hover:bg-blue-800 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">cloud_upload</span>
                Unggah Dokumen
            </button>
        </form>
    </div>

    @if($thesis->doc_sk_pembimbing_2)
    <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-500 text-[18px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
            <span class="text-sm font-medium text-slate-700">Dokumen sudah terupload</span>
        </div>
        <a href="{{ $thesis->getDownloadUrl('sk_pembimbing_2') }}" target="_blank" class="flex items-center gap-1.5 text-xs text-blue-700 font-semibold hover:underline">
            <span class="material-symbols-outlined text-[15px]">download</span> Unduh
        </a>
    </div>
    @endif
    @endif

    <div class="mt-5">
        <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali ke Dashboard
        </a>
    </div>
</div>

@endsection
