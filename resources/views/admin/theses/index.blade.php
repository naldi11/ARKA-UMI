@extends('layouts.app')

@section('title', 'Manajemen Skripsi')

@section('content')

<div class="space-y-8" x-data="{ 
    search: '', 
    showVerifyModal: false, 
    showAssignModal: false,
    showEditModal: false,
    selectedThesis: { id: null, title: '', jurnal: '', mhs_name: '', mhs_nim: '', dospem1: '', dospem2: '', status: '' }
}">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Data Skripsi</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Manajemen Skripsi</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Kelola repositori digital skripsi. Anda dapat mengatur pembimbing, memverifikasi dokumen final, atau mengubah judul skripsi jika diperlukan.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari skripsi..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64">
            </div>
        </div>
    </header>

    @if(session('status'))
        <div class="p-5 bg-success/5 text-success border border-success/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-5 bg-error/5 text-error border border-error/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-lg">error</span> {{ session('error') }}
        </div>
    @endif

    <div class="premium-card overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low">
            <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Dokumen Digital</h3>
            <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">Database Skripsi Nasional</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Judul Penelitian</th>
                        <th class="px-6 py-4">Status & Progress</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($theses as $thesis)
                    <tr x-show="'{{ strtolower($thesis->mahasiswa->user->name) }} {{ strtolower($thesis->title) }}'.includes(search.toLowerCase())"
                        class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-surface-container-high text-on-surface-variant flex items-center justify-center font-black text-xs">
                                    {{ strtoupper(substr($thesis->mahasiswa->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-black text-on-surface text-sm leading-tight">{{ $thesis->mahasiswa->user->name }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-bold opacity-50 mt-1 uppercase tracking-widest">{{ $thesis->mahasiswa->nim }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-on-surface leading-snug max-w-xs">{{ $thesis->title }}</p>
                            <p class="text-[10px] text-on-surface-variant font-bold mt-1 opacity-50 uppercase tracking-wider italic">{{ $thesis->jurnal_name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $realProgress = $thesis->calculateProgress();
                                $statusLabel = match($thesis->status) {
                                    'pending'         => ['label' => 'Menunggu Plotting', 'color' => 'bg-slate-100 text-slate-600 border-slate-200', 'progress' => '10%'],
                                    'approved'        => ['label' => 'Bimbingan (' . $realProgress . '%)', 'color' => 'bg-primary/10 text-primary border-primary/20', 'progress' => max(20, $realProgress) . '%'],
                                    'uploaded'        => ['label' => 'Verifikasi', 'color' => 'bg-accent-indigo/10 text-accent-indigo border-accent-indigo/20', 'progress' => '90%'],
                                    'finished'        => ['label' => 'Selesai', 'color' => 'bg-success/10 text-success border-success/20', 'progress' => '100%'],
                                    default           => ['label' => strtoupper($thesis->status), 'color' => 'bg-surface-container-high text-on-surface-variant border-outline-variant/10', 'progress' => '0%'],
                                };
                            @endphp
                            <div class="flex flex-col gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusLabel['color'] }} w-fit">
                                    {{ $statusLabel['label'] }}
                                </span>
                                <div class="w-24 h-1 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-primary transition-all duration-500" style="width: {{ $statusLabel['progress'] }}"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.theses.show', $thesis) }}"
                                    class="px-4 py-2 {{ $thesis->status === 'finished' ? 'bg-surface-container-high text-on-surface' : 'bg-primary text-on-primary' }} text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg {{ $thesis->status === 'finished' ? 'shadow-none' : 'shadow-primary/20 hover:bg-primary/90' }} transition-all">
                                    {{ $thesis->status === 'finished' ? 'Lihat Detail' : 'Verifikasi Berkas' }}
                                </a>
                                
                                @if($thesis->status !== 'finished')
                                <button
                                    @click="
                                        selectedThesis = { 
                                            id: {{ $thesis->id }}, 
                                            title: '{{ addslashes($thesis->title) }}',
                                            jurnal: '{{ addslashes($thesis->jurnal_name ?? '') }}',
                                            mhs_name: '{{ addslashes($thesis->mahasiswa->user->name) }}'
                                        };
                                        showEditModal = true;
                                    "
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface hover:bg-accent-indigo hover:text-white transition-all border border-outline-variant/10 shadow-sm"
                                    title="Ganti Judul">
                                    <span class="material-symbols-outlined text-[18px]">edit_note</span>
                                </button>
                                
                                <button
                                    @click="
                                        selectedThesis = { 
                                            id: {{ $thesis->id }}, 
                                            title: '{{ addslashes($thesis->title) }}',
                                            mhs_name: '{{ addslashes($thesis->mahasiswa->user->name) }}',
                                            mhs_nim: '{{ $thesis->mahasiswa->nim }}',
                                            dospem1: '{{ $thesis->supervisors->where('type', 1)->first()->dosen_id ?? '' }}',
                                            dospem2: '{{ $thesis->supervisors->where('type', 2)->first()->dosen_id ?? '' }}',
                                            status: '{{ $statusLabel['label'] }}'
                                        };
                                        showAssignModal = true;
                                        $nextTick(() => {
                                            $('#dospem1_select').val(selectedThesis.dospem1).trigger('change');
                                            $('#dospem2_select').val(selectedThesis.dospem2).trigger('change');
                                        });
                                    "
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface hover:bg-primary hover:text-white transition-all border border-outline-variant/10 shadow-sm"
                                    title="Atur Pembimbing">
                                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4">
                                    <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                </div>
                                <p class="text-on-surface-variant font-black">Repositori skripsi kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($theses->hasPages())
        <div class="px-6 py-4 bg-white border border-outline-variant/10 rounded-2xl">
            {{ $theses->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Ganti Judul -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-lg p-10 z-10 border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-16 h-16 bg-accent-indigo/10 rounded-2xl flex items-center justify-center text-accent-indigo border border-accent-indigo/10 shadow-sm">
                    <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">edit_note</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter">Ganti Judul Skripsi</h3>
                    <p class="text-sm text-on-surface-variant font-medium">Ubah judul atau target jurnal penelitian</p>
                </div>
            </div>

            <form :action="'/admin/skripsi/' + selectedThesis.id" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Judul Skripsi Baru</label>
                    <textarea name="title" x-model="selectedThesis.title" rows="4" required
                        class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Target Jurnal</label>
                    <input type="text" name="jurnal_name" x-model="selectedThesis.jurnal"
                        class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-accent-indigo text-white text-sm font-black rounded-2xl shadow-lg shadow-accent-indigo/20 hover:bg-accent-indigo/90 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Atur Pembimbing (Detailed) -->
    <div x-show="showAssignModal" x-cloak id="assignModalContainer" class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="showAssignModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-xl overflow-hidden border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-primary"></div>
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                            <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">manage_accounts</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-on-surface tracking-tighter">Atur Pembimbing</h3>
                            <p class="text-sm text-on-surface-variant font-medium">Tugaskan dosen pembimbing skripsi</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-5 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                        <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-3 opacity-50">Data Mahasiswa</p>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-outline-variant/10 flex items-center justify-center text-xs font-black text-primary shadow-sm" x-text="selectedThesis.mhs_name ? selectedThesis.mhs_name.substring(0,2).toUpperCase() : ''"></div>
                            <div>
                                <p x-text="selectedThesis.mhs_name" class="text-sm font-black text-on-surface leading-tight"></p>
                                <p x-text="selectedThesis.mhs_nim" class="text-[10px] font-bold text-on-surface-variant tracking-widest mt-0.5"></p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                        <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-3 opacity-50">Judul Penelitian</p>
                        <p x-text="selectedThesis.title" class="text-xs font-bold text-on-surface leading-relaxed line-clamp-2 italic"></p>
                    </div>
                </div>

                <form :action="'/admin/skripsi/' + selectedThesis.id + '/tugaskan'" method="POST" id="assignForm" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Dosen Pembimbing 1</label>
                            <select name="dospem1_id" id="dospem1_select" required class="select2-dosen">
                                <option value="">Pilih Dosen 1</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama_gelar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Dosen Pembimbing 2</label>
                            <select name="dospem2_id" id="dospem2_select" required class="select2-dosen">
                                <option value="">Pilih Dosen 2</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama_gelar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="validationError" class="hidden p-4 bg-error/5 text-error border border-error/10 rounded-2xl text-[11px] font-black flex items-center gap-3">
                        <span class="material-symbols-outlined text-lg">warning</span>
                        Dosen Pembimbing 1 dan 2 tidak boleh sama!
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showAssignModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-primary text-on-primary text-sm font-black rounded-2xl shadow-lg shadow-primary/20 transition-all">
                            Simpan Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-dosen').select2({
            dropdownParent: $('#assignModalContainer'),
            width: '100%'
        });

        $('#assignForm').on('submit', function(e) {
            const d1 = $('#dospem1_select').val();
            const d2 = $('#dospem2_select').val();
            if (d1 === d2 && d1 !== '') {
                e.preventDefault();
                $('#validationError').removeClass('hidden').addClass('animate-in shake duration-300');
                return false;
            }
        });
    });
</script>
@endpush

@endsection
