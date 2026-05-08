@extends('layouts.app')

@section('title', 'Tahap 3: Kelulusan')

@section('content')
<div class="space-y-6" x-data="{ 
    showPreviewModal: false,
    previewUrl: '',
    previewLabel: '',
    previewExtension: ''
}">
    <!-- Header Section -->
    <header class="relative p-8 bg-white rounded-[32px] shadow-premium-sm border border-outline-variant/10 overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-success/5 rounded-full blur-3xl group-hover:bg-success/10 transition-all duration-700"></div>
        <div class="relative z-10">
            <nav class="flex items-center gap-2 text-[10px] font-black text-on-surface-variant/50 mb-3 uppercase tracking-[0.2em]">
                <span>Pusat Unggahan</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-success font-black">Tahap 3 (Final)</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tighter text-on-surface mb-2 leading-tight">Penyelesaian Akhir</h1>
            <p class="text-on-surface-variant font-medium text-sm max-w-2xl leading-relaxed">
                Tahap terakhir. Unggah berkas sidang meja hijau dan arsip digital (CD) Anda.
            </p>
        </div>
    </header>

    @if(session('status'))
        <div class="p-5 bg-success text-white rounded-2xl text-sm font-black flex items-center gap-4 shadow-lg shadow-success/20 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-2xl">verified</span> {{ session('status') }}
        </div>
    @endif

    <div class="premium-card p-8 bg-gradient-to-b from-surface-container-lowest to-white border-outline-variant/10">
        <form action="{{ route('mahasiswa.upload.kelulusan') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                    ['key' => 'doc_meja_hijau', 'label' => 'Berkas Meja Hijau', 'icon' => 'verified_user', 'max' => '10MB', 'type' => 'PDF/Word/Teks'],
                    ['key' => 'doc_cd',         'label' => 'CD Skripsi (Digital)', 'icon' => 'album', 'max' => '50MB', 'type' => 'ZIP/RAR/PDF/Word'],
                ] as $doc)
                @php
                    $field = $doc['key'];
                    $folder = str_replace('doc_', '', $field);
                    $vData = ($thesis->verification_data ?? [])[$folder] ?? null;
                    
                    $path = $thesis->$field;
                    $vStatus = $vData['status'] ?? ($path ? 'pending' : 'empty');
                    $vNotes = $vData['notes'] ?? '';

                    $stateCfg = [
                        'empty' => [
                            'bg' => 'bg-surface-container-low border-outline-variant/20',
                            'iconBg' => 'bg-success/5 text-success',
                            'icon' => $doc['icon'],
                            'label' => 'Pilih File ' . $doc['type'],
                            'statusText' => 'Belum Diunggah',
                            'statusColor' => 'text-on-surface-variant/40'
                        ],
                        'pending' => [
                            'bg' => 'bg-warning/5 border-warning/20',
                            'iconBg' => 'bg-warning/10 text-warning',
                            'icon' => 'hourglass_top',
                            'label' => 'Update File?',
                            'statusText' => 'Validasi Akhir Admin',
                            'statusColor' => 'text-warning'
                        ],
                        'accepted' => [
                            'bg' => 'bg-success/10 border-success/30 shadow-lg shadow-success/5 opacity-80',
                            'iconBg' => 'bg-success text-white',
                            'icon' => 'check_circle',
                            'label' => 'Arsip Diterima (Terkunci)',
                            'statusText' => 'Lengkap & Valid',
                            'statusColor' => 'text-success'
                        ],
                        'rejected' => [
                            'bg' => 'bg-error/5 border-error/20',
                            'iconBg' => 'bg-error/10 text-error',
                            'icon' => 'gavel',
                            'label' => 'Unggah Ulang',
                            'statusText' => 'Perlu Koreksi',
                            'statusColor' => 'text-error'
                        ]
                    ];
                    $cfg = $stateCfg[$vStatus];
                @endphp

                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em] px-2">{{ $doc['label'] }}</label>
                    
                    <div class="relative group">
                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="hidden" onchange="updateFileName(this)" {{ $vStatus === 'accepted' ? 'disabled' : '' }}>
                        <label for="{{ $field }}" class="flex flex-col items-center justify-center p-10 border-2 border-dashed rounded-[24px] transition-all duration-500 group relative overflow-hidden
                            {{ $vStatus === 'accepted' ? 'cursor-not-allowed' : 'cursor-pointer hover:bg-white hover:border-success/50' }}
                            {{ $cfg['bg'] }}">
                            
                            <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-white/80 backdrop-blur-md shadow-sm border border-outline-variant/10">
                                <span class="text-[8px] font-black uppercase tracking-widest {{ $cfg['statusColor'] }}">{{ $cfg['statusText'] }}</span>
                            </div>

                            <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-3 transition-all duration-500 group-hover:scale-110 {{ $cfg['iconBg'] }} relative">
                                <span class="material-symbols-outlined text-3xl">
                                     {{ $cfg['icon'] }}
                                </span>
                                @if($vStatus === 'accepted')
                                    <div class="absolute -right-2 -top-2 w-6 h-6 bg-success text-white rounded-full flex items-center justify-center shadow-lg border-2 border-white">
                                        <span class="material-symbols-outlined text-[14px]">lock</span>
                                    </div>
                                @endif
                            </div>

                            <span class="text-xs font-black text-on-surface text-center px-4" id="name_{{ $field }}">
                                {{ $cfg['label'] }}
                            </span>
                            
                            @if($path)
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-[9px] text-on-surface-variant font-bold mt-1 opacity-60">Arsip: {{ basename($path) }}</span>
                                <button type="button" @click.stop="previewUrl = '{{ $thesis->getDownloadUrl($folder) }}'; previewLabel = '{{ $doc['label'] }}'; previewExtension = '{{ $thesis->getFileExtension($folder) }}'; showPreviewModal = true"
                                    class="flex items-center gap-2 px-3 py-1.5 bg-white border border-outline-variant/10 rounded-full text-[9px] font-black uppercase tracking-widest text-on-surface hover:bg-success hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-sm">visibility</span> Lihat Berkas
                                </button>
                            </div>
                            @else
                            <span class="text-[9px] text-on-surface-variant font-bold mt-1 opacity-60">Maksimal {{ $doc['max'] }}</span>
                            @endif
                        </label>
                    </div>

                    @if($vStatus === 'rejected')
                        <div class="p-4 bg-error text-white rounded-2xl flex gap-4 shadow-lg shadow-error/20 animate-in slide-in-from-bottom-2">
                            <span class="material-symbols-outlined shrink-0 text-2xl text-white">gavel</span>
                            <p class="text-xs font-bold leading-relaxed">"{{ $vNotes }}"</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="pt-8 border-t border-outline-variant/10 flex flex-col sm:flex-row justify-between items-center gap-6">
                <a href="{{ route('mahasiswa.upload.penelitian.page') }}" class="flex items-center gap-3 px-6 py-4 text-xs font-black text-on-surface-variant hover:text-primary transition-all group uppercase tracking-widest">
                    <span class="material-symbols-outlined group-hover:-translate-x-2 transition-transform text-lg">arrow_back</span>
                    Ke Tahap 2
                </a>
                
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-success text-white rounded-2xl font-black shadow-xl shadow-on-surface/20 hover:bg-emerald-600 transition-all duration-300 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-xs uppercase tracking-widest">
                    <span class="material-symbols-outlined text-lg">verified_user</span>
                    Simpan Berkas
                </button>
            </div>
        </form>
    </div>
    <!-- Canvas Preview (Full Screen Overlay) -->
    <div x-show="showPreviewModal" x-cloak 
         class="fixed inset-0 z-[1000] flex items-center justify-center p-0 md:p-8 overflow-hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <div class="absolute inset-0 bg-on-surface/95 backdrop-blur-md" @click="showPreviewModal = false"></div>
        
        <div class="relative bg-white w-full h-full md:rounded-[40px] shadow-[0_0_80px_rgba(0,0,0,0.5)] z-10 flex flex-col overflow-hidden animate-in zoom-in-95 duration-500">
            <div class="px-8 h-20 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-lowest shrink-0">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-outlined text-2xl">visibility</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-on-surface tracking-tighter" x-text="previewLabel"></h3>
                        <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Dokumen Digital Saya</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a :href="previewUrl + '&download=1'" class="flex items-center gap-2 px-5 py-3 bg-surface-container-high hover:bg-surface-container-highest text-on-surface rounded-2xl font-black text-xs transition-all border border-outline-variant/10">
                        <span class="material-symbols-outlined text-[18px]">file_download</span> Download
                    </a>
                    <button @click="showPreviewModal = false" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-error/10 text-error hover:bg-error hover:text-white transition-all group shadow-sm shadow-error/10">
                        <span class="material-symbols-outlined text-2xl group-hover:rotate-90 transition-transform">close</span>
                    </button>
                </div>
            </div>
            
            <div class="flex-1 bg-slate-100 relative overflow-hidden">
                <template x-if="['zip', 'rar'].includes(previewExtension)">
                    <div class="w-full h-full flex flex-col items-center justify-center p-20 bg-gradient-to-br from-slate-50 to-slate-100">
                        <div class="w-32 h-32 bg-white rounded-[40px] shadow-premium-lg flex items-center justify-center mb-8 border border-outline-variant/10">
                            <span class="material-symbols-outlined text-6xl text-primary animate-bounce">folder_zip</span>
                        </div>
                        <h4 class="text-2xl font-black text-on-surface tracking-tighter mb-2">Arsip Digital (ZIP/RAR)</h4>
                        <p class="text-sm text-on-surface-variant font-medium mb-10 max-w-sm text-center leading-relaxed">Browser tidak dapat menampilkan isi file kompresi secara langsung. Silakan unduh untuk melihat isinya.</p>
                        
                        <a :href="previewUrl + '&download=1'" class="flex items-center gap-4 px-10 py-5 bg-on-surface text-white rounded-[32px] font-black shadow-2xl shadow-on-surface/20 hover:bg-primary transition-all active:scale-95">
                            <span class="material-symbols-outlined">download</span>
                            Download Arsip Sekarang
                        </a>
                    </div>
                </template>
                
                <template x-if="!['zip', 'rar'].includes(previewExtension)">
                    <div class="w-full h-full relative">
                        <iframe :src="previewUrl" class="w-full h-full border-none shadow-inner" title="Dokumen Preview"></iframe>
                        
                        <div class="absolute inset-0 flex items-center justify-center bg-white z-[-1]">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                                <p class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Memuat Dokumen...</p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const id = input.id;
        const nameDisplay = document.getElementById('name_' + id);
        if (input.files.length > 0) {
            nameDisplay.innerText = input.files[0].name;
            nameDisplay.classList.add('text-success');
            nameDisplay.closest('label').classList.add('ring-4', 'ring-success/10', 'border-success/50');
        }
    }
</script>
@endsection
