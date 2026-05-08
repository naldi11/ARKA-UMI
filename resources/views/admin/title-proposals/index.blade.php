@extends('layouts.app')

@section('title', 'Konfirmasi Judul Skripsi')

@section('content')

<div class="space-y-8" x-data="{ 
    search: '', 
    tab: 'pending',
    showApproveModal: false,
    showRejectModal: false,
    selectedProposal: { id: null, title: '', mhs: '' }
}">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Konfirmasi Judul</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Konfirmasi Judul</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Tinjau dan setujui pengajuan judul mahasiswa. Jika satu judul disetujui, judul lain dari mahasiswa yang sama akan otomatis ditolak oleh sistem.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari NIM, Nama, atau Judul..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-80 shadow-sm">
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
            <span class="material-symbols-outlined text-lg">warning</span> {{ session('error') }}
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="premium-card overflow-hidden">
        <!-- Tab Header -->
        <div class="px-6 py-4 border-b border-outline-variant/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-container-low">
            <div class="flex items-center gap-2 bg-surface-container-high rounded-xl p-1 w-fit border border-outline-variant/5">
                <button @click="tab = 'pending'" :class="tab === 'pending' ? 'bg-white shadow-sm text-primary font-black' : 'text-on-surface-variant font-bold opacity-60'" class="px-4 py-2 rounded-lg text-xs transition-all">
                    Menunggu
                </button>
                <button @click="tab = 'approved'" :class="tab === 'approved' ? 'bg-white shadow-sm text-success font-black' : 'text-on-surface-variant font-bold opacity-60'" class="px-4 py-2 rounded-lg text-xs transition-all">
                    Disetujui
                </button>
                <button @click="tab = 'rejected'" :class="tab === 'rejected' ? 'bg-white shadow-sm text-error font-black' : 'text-on-surface-variant font-bold opacity-60'" class="px-4 py-2 rounded-lg text-xs transition-all">
                    Ditolak
                </button>
                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-white shadow-sm text-on-surface font-black' : 'text-on-surface-variant font-bold opacity-60'" class="px-4 py-2 rounded-lg text-xs transition-all">
                    Semua
                </button>
            </div>
            <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">Database Pengajuan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Judul yang Diajukan</th>
                        <th class="px-6 py-4">Target Jurnal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($proposals as $proposal)
                    <tr x-show="(tab === 'all' || tab === '{{ $proposal->status }}') && ('{{ strtolower($proposal->mahasiswa->user->name ?? '') }} {{ strtolower($proposal->mahasiswa->nim ?? '') }} {{ strtolower($proposal->title) }}'.includes(search.toLowerCase()))" 
                        class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-black text-xs shadow-sm">
                                    {{ strtoupper(substr($proposal->mahasiswa->user->name ?? 'M', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-on-surface leading-tight">{{ $proposal->mahasiswa->user->name ?? '-' }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-bold tracking-widest mt-1">{{ $proposal->mahasiswa->nim ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-on-surface leading-snug max-w-xs">{{ $proposal->title }}</p>
                            <p class="text-[10px] text-on-surface-variant font-bold mt-1 opacity-50 uppercase tracking-wider">{{ $proposal->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-xs font-bold text-on-surface-variant italic opacity-70">{{ $proposal->jurnal_name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusStyle = match($proposal->status) {
                                    'pending'  => 'bg-warning/10 text-warning border-warning/20',
                                    'approved' => 'bg-success/10 text-success border-success/20',
                                    'rejected' => 'bg-error/10 text-error border-error/20',
                                    default    => 'bg-surface-container-high text-on-surface-variant border-outline-variant/10'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusStyle }}">
                                {{ $proposal->status === 'pending' ? 'Menunggu' : ($proposal->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($proposal->status === 'pending')
                                    <button
                                        @click="
                                            selectedProposal = { 
                                                id: {{ $proposal->id }}, 
                                                title: '{{ addslashes($proposal->title) }}',
                                                mhs: '{{ addslashes($proposal->mahasiswa->user->name ?? '') }} ({{ $proposal->mahasiswa->nim ?? '' }})'
                                            };
                                            showApproveModal = true;
                                        "
                                        class="px-4 py-2 bg-success text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-success/20 hover:bg-success/90 transition-all">
                                        Terima
                                    </button>
                                    <button
                                        @click="
                                            selectedProposal = { 
                                                id: {{ $proposal->id }}, 
                                                title: '{{ addslashes($proposal->title) }}',
                                                mhs: '{{ addslashes($proposal->mahasiswa->user->name ?? '') }}'
                                            };
                                            showRejectModal = true;
                                        "
                                        class="px-4 py-2 bg-surface-container-high text-error text-[11px] font-black uppercase tracking-widest rounded-xl border border-error/10 hover:bg-error hover:text-white transition-all">
                                        Tolak
                                    </button>
                                @elseif($proposal->status === 'rejected')
                                    <span class="text-[10px] text-on-surface-variant font-medium italic opacity-40" title="{{ $proposal->rejection_reason }}">Ditolak</span>
                                @else
                                    <span class="material-symbols-outlined text-success text-lg" title="Sudah Disetujui">check_circle</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4">
                                    <span class="material-symbols-outlined text-4xl">inbox</span>
                                </div>
                                <p class="text-on-surface-variant font-black uppercase tracking-widest opacity-50">Data Kosong</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($proposals->hasPages())
    <div class="px-6 py-4 bg-white border border-outline-variant/10 rounded-2xl">
        {{ $proposals->links() }}
    </div>
    @endif

    <!-- Modal Setujui -->
    <div x-show="showApproveModal" x-cloak id="approveModalContainer" class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="showApproveModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-lg p-10 z-10 border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="flex items-center gap-5 mb-10">
                <div class="w-16 h-16 bg-success/10 rounded-2xl flex items-center justify-center text-success border border-success/10 shadow-sm">
                    <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">check_circle</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-on-surface tracking-tighter">Setujui Judul</h3>
                    <p class="text-sm text-on-surface-variant font-medium">Lengkapi penugasan dosen pembimbing</p>
                </div>
            </div>

            <div class="bg-surface-container-low rounded-2xl p-6 mb-8 border border-outline-variant/10">
                <div class="mb-4 pb-4 border-b border-outline-variant/5">
                    <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-1 opacity-50">Mahasiswa</p>
                    <p x-text="selectedProposal.mhs" class="text-sm font-black text-on-surface"></p>
                </div>
                <p class="text-[10px] text-on-surface-variant uppercase font-black tracking-widest mb-1 opacity-50">Judul Skripsi</p>
                <p x-text="selectedProposal.title" class="text-sm font-bold text-on-surface leading-relaxed italic"></p>
                <p class="text-[10px] text-error font-black uppercase mt-6 tracking-widest leading-relaxed">
                    * Judul lain dari mahasiswa ini akan otomatis ditolak.
                </p>
            </div>

            <form :action="'/admin/konfirmasi-judul/' + selectedProposal.id + '/setujui'" method="POST" id="approveForm" class="space-y-6">
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
                    <button type="button" @click="showApproveModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-success text-white text-sm font-black rounded-2xl shadow-lg shadow-success/20 hover:shadow-success-lg transition-all hover:-translate-y-0.5">
                        Konfirmasi & Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm" @click="showRejectModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-md p-8 z-10 border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 bg-error/10 rounded-2xl flex items-center justify-center text-error border border-error/10 shadow-sm">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL' 1;">cancel</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-on-surface tracking-tighter">Tolak Judul</h3>
                    <p class="text-sm text-on-surface-variant font-medium">Berikan alasan penolakan</p>
                </div>
            </div>

            <div class="bg-surface-container-low rounded-2xl p-5 mb-8 border border-outline-variant/10">
                <p x-text="selectedProposal.title" class="text-sm font-bold text-on-surface leading-relaxed"></p>
            </div>

            <form :action="'/admin/konfirmasi-judul/' + selectedProposal.id + '/tolak'" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="3" placeholder="Contoh: Judul sudah terlalu umum..."
                        class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showRejectModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-4 bg-error text-white text-sm font-black rounded-2xl shadow-lg shadow-error/20 hover:bg-error-lg transition-all">
                        Tolak Judul
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-dosen').select2({
            dropdownParent: $('#approveModalContainer'),
            width: '100%'
        });

        $('#approveForm').on('submit', function(e) {
            const d1 = $('#dospem1_select').val();
            const d2 = $('#dospem2_select').val();
            if (d1 === d2 && d1 !== '') {
                e.preventDefault();
                $('#validationError').removeClass('hidden').addClass('animate-in shake');
                return false;
            }
        });
    });
</script>
@endpush

@endsection
