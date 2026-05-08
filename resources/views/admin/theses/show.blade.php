@extends('layouts.app')

@section('title', 'Verifikasi Dokumen - ' . $thesis->mahasiswa->user->name)

@section('content')
<div class="space-y-8" x-data="{ 
    showReviewModal: false, 
    showPreviewModal: false,
    previewUrl: '',
    previewLabel: '',
    previewExtension: '',
    selectedDoc: { key: '', label: '', status: '', notes: '' } 
}">
    <!-- Header Section -->
    <div class="relative p-10 bg-white rounded-[40px] shadow-premium-sm border border-outline-variant/10 overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
        <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-accent-indigo/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="flex items-start gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-primary to-accent-indigo text-white rounded-3xl flex items-center justify-center font-black text-3xl shadow-2xl shadow-primary/30 ring-4 ring-white">
                    {{ strtoupper(substr($thesis->mahasiswa->user->name, 0, 1)) }}
                </div>
                <div>
                    <nav class="flex items-center gap-2 text-[10px] font-black text-on-surface-variant/50 mb-3 uppercase tracking-[0.2em]">
                        <a href="{{ route('admin.theses.index') }}" class="hover:text-primary transition-colors">Data Skripsi</a>
                        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                        <span class="text-primary">Verifikasi Digital</span>
                    </nav>
                    <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2 leading-none">{{ $thesis->mahasiswa->user->name }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-on-surface-variant">{{ $thesis->mahasiswa->nim }}</span>
                        <span class="w-1 h-1 bg-outline-variant rounded-full"></span>
                        <span class="px-3 py-1 bg-surface-container-high rounded-full text-[10px] font-black text-on-surface uppercase tracking-widest">{{ $thesis->status }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block mr-4">
                    <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Kelengkapan Berkas</p>
                    <div class="w-32 h-2 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-1000" style="width: {{ ($thesis->uploadedCount() / 8) * 100 }}%"></div>
                    </div>
                </div>
                <a href="{{ route('admin.theses.index') }}" class="flex items-center gap-2 px-6 py-4 bg-surface-container-high text-on-surface rounded-2xl font-black text-xs transition-all hover:bg-surface-container-highest border border-outline-variant/10 shadow-sm">
                    <span class="material-symbols-outlined text-xl">arrow_back</span> Kembali
                </a>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="p-5 bg-success text-white rounded-3xl text-sm font-black flex items-center gap-4 shadow-lg shadow-success/20 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined">check_circle</span> {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Final Verdict -->
        <div class="space-y-6">
            <div class="premium-card p-10 bg-gradient-to-b from-surface-container-low to-white border-outline-variant/10">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-8 border border-primary/10">
                    <span class="material-symbols-outlined text-3xl">verified_user</span>
                </div>
                <h3 class="text-xl font-black text-on-surface tracking-tight mb-2">Keputusan Akhir</h3>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed mb-8">Selesaikan skripsi ini hanya jika seluruh dokumen di samping telah diverifikasi dengan benar.</p>
                
                @if($thesis->status !== 'finished')
                <form action="{{ route('admin.theses.verify', $thesis) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest px-1">Catatan Akhir Mahasiswa</label>
                        <textarea name="notes" rows="4" class="w-full px-5 py-4 bg-white border border-outline-variant/20 rounded-3xl text-sm font-bold focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none resize-none" placeholder="Tulis catatan penutup..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <button type="submit" name="action" value="finish" class="w-full py-5 bg-primary text-on-primary text-sm font-black rounded-3xl shadow-xl shadow-primary/20 hover:shadow-primary-lg transition-all flex items-center justify-center gap-3 active:scale-95">
                            <span class="material-symbols-outlined">verified</span> Terima & Arsipkan
                        </button>
                        <button type="submit" name="action" value="reject" class="w-full py-5 bg-error/10 text-error text-sm font-black rounded-3xl hover:bg-error hover:text-white transition-all flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined">block</span> Tolak Revisi Total
                        </button>
                    </div>
                </form>
                @else
                <div class="mt-8 p-6 bg-success/10 border border-success/20 rounded-3xl text-center">
                    <span class="material-symbols-outlined text-4xl text-success mb-3">task_alt</span>
                    <h4 class="text-sm font-black text-success uppercase tracking-widest">Skripsi Selesai</h4>
                    <p class="text-[10px] text-success/70 font-bold mt-1">Data telah diarsipkan dan tidak dapat diubah kembali.</p>
                </div>
                @endif
            </div>

            <!-- Research Information -->
            <div class="premium-card p-8 border-outline-variant/10 bg-white">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 bg-accent-indigo/10 rounded-xl flex items-center justify-center text-accent-indigo">
                        <span class="material-symbols-outlined text-xl">info</span>
                    </div>
                    <h4 class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Informasi Penelitian</h4>
                </div>
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest mb-1">Judul Skripsi</p>
                        <p class="text-xs font-bold text-on-surface leading-relaxed">{{ $thesis->title }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest mb-1">Target Jurnal</p>
                        <p class="text-xs font-bold text-primary italic">{{ $thesis->jurnal_name ?? 'Tidak Ada' }}</p>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/5 space-y-4">
                        @foreach($thesis->supervisors as $sup)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-[10px] font-black text-on-surface-variant">
                                {{ $sup->type }}
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-widest">Pembimbing {{ $sup->type }}</p>
                                <p class="text-[11px] font-black text-on-surface">{{ $sup->dosen->nama_gelar }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="premium-card p-8 flex items-center justify-between border-outline-variant/10">
                <div>
                    <h4 class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Total Berkas</h4>
                    <p class="text-3xl font-black text-on-surface mt-1">{{ $thesis->uploadedCount() }} <span class="text-xs text-on-surface-variant">/ 8</span></p>
                </div>
                <div class="w-14 h-14 bg-success/10 text-success rounded-2xl flex items-center justify-center border border-success/10">
                    <span class="material-symbols-outlined text-3xl">task_alt</span>
                </div>
            </div>
        </div>

        <!-- Main: Document List -->
        <div class="lg:col-span-2 space-y-6">
            <div class="premium-card overflow-hidden">
                <div class="px-10 py-8 border-b border-outline-variant/10 bg-surface-container-low flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-on-surface tracking-tight">Daftar Berkas Digital</h3>
                        <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mt-1">Verifikasi berkas secara individual</p>
                    </div>
                </div>

                <div class="divide-y divide-outline-variant/5">
                    @php
                        $docs = [
                            ['key' => 'sk_pembimbing_1',   'label' => 'SK Pembimbing 1',       'icon' => 'assignment_ind'],
                            ['key' => 'sk_pembimbing_2',   'label' => 'SK Pembimbing 2',       'icon' => 'assignment_ind'],
                            ['key' => 'target_jurnal',     'label' => 'Judul & Target Jurnal', 'icon' => 'description'],
                            ['key' => 'izin_penelitian',   'label' => 'Surat Izin Penelitian', 'icon' => 'badge'],
                            ['key' => 'jurnal',            'label' => 'Jurnal Skripsi',         'icon' => 'article'],
                            ['key' => 'skripsi',           'label' => 'Dokumen Skripsi',       'icon' => 'picture_as_pdf'],
                            ['key' => 'meja_hijau',        'label' => 'Dokumen Meja Hijau',    'icon' => 'verified'],
                            ['key' => 'cd',                'label' => 'CD Skripsi',            'icon' => 'album'],
                        ];
                    @endphp

                    @foreach($docs as $doc)
                    @php
                        $key = $doc['key'];
                        $field = 'doc_' . $key;
                        $path = $thesis->$field;
                        $vData = ($thesis->verification_data ?? [])[$key] ?? null;
                        $vStatus = $vData['status'] ?? 'pending';
                        $vNotes = $vData['notes'] ?? '';
                    @endphp
                    <div class="px-10 py-8 flex flex-col md:flex-row md:items-center justify-between gap-8 hover:bg-surface-container-lowest transition-all group">
                        <div class="flex items-start gap-6">
                            @php
                                $ext = $path ? $thesis->getFileExtension($key) : null;
                                $icon = $doc['icon'];
                                if ($ext === 'pdf') $icon = 'picture_as_pdf';
                                elseif (in_array($ext, ['doc', 'docx'])) $icon = 'description';
                                elseif (in_array($ext, ['zip', 'rar'])) $icon = 'folder_zip';
                                elseif (in_array($ext, ['txt'])) $icon = 'article';
                            @endphp
                            <div class="w-14 h-14 shrink-0 rounded-2xl flex items-center justify-center border-2 transition-all duration-500
                                @if(!$path) bg-slate-50 border-slate-100 text-slate-300
                                @elseif($vStatus === 'accepted') bg-success/5 border-success/20 text-success shadow-lg shadow-success/10
                                @elseif($vStatus === 'rejected') bg-error/5 border-error/20 text-error shadow-lg shadow-error/10
                                @else bg-primary/5 border-primary/20 text-primary shadow-lg shadow-primary/10
                                @endif">
                                <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h4 class="text-base font-black text-on-surface leading-tight">{{ $doc['label'] }}</h4>
                                    @if($path)
                                        <div class="flex items-center gap-1.5">
                                            @if($vStatus === 'accepted')
                                                <span class="flex items-center gap-1 px-2.5 py-0.5 bg-success text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-success/20">
                                                    <span class="material-symbols-outlined text-[12px]">check_circle</span> Diterima
                                                </span>
                                            @elseif($vStatus === 'rejected')
                                                <span class="flex items-center gap-1 px-2.5 py-0.5 bg-error text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-error/20">
                                                    <span class="material-symbols-outlined text-[12px]">cancel</span> Ditolak
                                                </span>
                                            @else
                                                <span class="flex items-center gap-1 px-2.5 py-0.5 bg-warning text-on-warning-container text-[9px] font-black uppercase tracking-widest rounded-full border border-warning/30">
                                                    <span class="material-symbols-outlined text-[12px]">pending</span> Menunggu
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <p class="text-[10px] font-black mt-1.5 uppercase tracking-widest {{ $path ? 'text-on-surface-variant' : 'text-slate-400' }}">
                                    {{ $path ? 'Update: ' . $thesis->updated_at->diffForHumans() : 'Mahasiswa belum mengunggah berkas ini' }}
                                </p>
                                @if($vNotes && $vStatus === 'rejected')
                                    <div class="mt-3 p-3 bg-error/5 border-l-4 border-error rounded-r-xl">
                                        <p class="text-[11px] font-bold text-error">"{{ $vNotes }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($path)
                                <button @click="previewUrl = '{{ $thesis->getDownloadUrl($doc['key']) }}'; previewLabel = '{{ $doc['label'] }}'; previewExtension = '{{ $thesis->getFileExtension($doc['key']) }}'; showPreviewModal = true"
                                    class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-outline-variant/20 text-on-surface shadow-sm hover:bg-primary hover:text-white hover:border-primary transition-all duration-300">
                                    <span class="material-symbols-outlined text-2xl">visibility</span>
                                </button>
                                @if($thesis->status !== 'finished')
                                <button @click="selectedDoc = { key: '{{ $doc['key'] }}', label: '{{ $doc['label'] }}', status: '{{ $vStatus }}', notes: '{{ $vNotes }}' }; showReviewModal = true"
                                    class="px-6 py-3.5 bg-white border border-outline-variant/20 rounded-2xl text-[11px] font-black uppercase tracking-widest text-on-surface hover:bg-surface-container-high transition-all shadow-sm active:scale-95">
                                    Review
                                </button>
                                @endif
                            @else
                                <div class="px-6 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-[11px] font-black uppercase tracking-widest text-slate-300 cursor-not-allowed">
                                    Kosong
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
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
        
        <!-- Canvas Container -->
        <div class="relative bg-white w-full h-full md:rounded-[40px] shadow-[0_0_80px_rgba(0,0,0,0.5)] z-10 flex flex-col overflow-hidden animate-in zoom-in-95 duration-500">
            <!-- Canvas Header -->
            <div class="px-8 h-20 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-lowest shrink-0">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-outlined text-2xl">visibility</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-on-surface tracking-tighter" x-text="previewLabel"></h3>
                        <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.2em]">Dokumen Digital Mahasiswa</p>
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
            
            <!-- Canvas Body (Iframe or Download Placeholder) -->
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
                        
                        <!-- Loading State -->
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

    <!-- Review Modal -->
    <div x-show="showReviewModal" x-cloak class="fixed inset-0 z-[1100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="showReviewModal = false"></div>
        <div class="relative bg-white rounded-[40px] shadow-premium-lg w-full max-w-lg p-10 z-10 border border-outline-variant/10 animate-in slide-in-from-bottom-8 duration-300">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-accent-indigo/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                    <span class="material-symbols-outlined text-4xl">fact_check</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter" x-text="selectedDoc.label"></h3>
                    <p class="text-xs text-on-surface-variant font-medium">Tentukan validitas dokumen ini</p>
                </div>
            </div>

            <form action="{{ route('admin.theses.review-document', $thesis) }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="doc_key" :value="selectedDoc.key">
                
                <div class="space-y-4">
                    <label class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-2">Hasil Verifikasi</label>
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="accepted" x-model="selectedDoc.status" class="hidden peer">
                            <div class="p-6 border-2 border-transparent bg-surface-container-low rounded-3xl peer-checked:bg-success/10 peer-checked:border-success peer-checked:text-success transition-all text-center group">
                                <div class="w-10 h-10 bg-white rounded-xl mx-auto mb-3 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest">Diterima</p>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="rejected" x-model="selectedDoc.status" class="hidden peer">
                            <div class="p-6 border-2 border-transparent bg-surface-container-low rounded-3xl peer-checked:bg-error/10 peer-checked:border-error peer-checked:text-error transition-all text-center group">
                                <div class="w-10 h-10 bg-white rounded-xl mx-auto mb-3 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-2xl">cancel</span>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest">Ditolak</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-3" x-show="selectedDoc.status === 'rejected'">
                    <label class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-2">Alasan Penolakan</label>
                    <textarea name="notes" x-model="selectedDoc.notes" rows="4" class="w-full px-6 py-5 bg-surface-container-low border border-transparent rounded-[32px] text-sm font-bold focus:bg-white focus:border-error focus:ring-4 focus:ring-error/10 transition-all outline-none resize-none" placeholder="Jelaskan kekurangan dokumen..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="showReviewModal = false" class="flex-1 py-5 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-3xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-5 bg-on-surface text-white rounded-3xl font-black shadow-xl shadow-on-surface/20 hover:bg-primary transition-all text-sm active:scale-95">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
