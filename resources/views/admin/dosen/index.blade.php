@extends('layouts.app')

@section('title', 'Manajemen Dosen')

@section('content')

<div class="space-y-8" x-data="{ 
    search: '', 
    showAddModal: false, 
    showEditModal: false,
    editData: { id: null, nip: '', nama: '', email: '' }
}">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Kelola Dosen</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Manajemen Dosen</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Kelola repositori data dosen pembimbing. Anda dapat menambah, memperbarui, atau menghapus personel dari sistem otoritas skripsi.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari NIP atau Nama..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64 shadow-sm">
            </div>
            <button @click="showAddModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl font-black text-sm shadow-premium hover:shadow-premium-lg transition-all hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                Tambah Dosen
            </button>
        </div>
    </header>

    @if(session('status'))
        <div class="p-5 bg-success/5 text-success border border-success/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 mb-6">
            <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('status') }}
        </div>
    @endif

    <!-- Full Width Table -->
    <div class="premium-card overflow-hidden">
        <div class="px-8 py-6 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low">
            <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Daftar Dosen Aktif</h3>
            <div class="flex items-center gap-4">
                <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">{{ $dosens->total() }} Personel Terdaftar</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-8 py-4">Profil & Identitas</th>
                        <th class="px-8 py-4">NIP Dosen</th>
                        <th class="px-8 py-4">Kontak & Akses</th>
                        <th class="px-8 py-4 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($dosens as $dosen)
                    <tr x-show="'{{ strtolower($dosen->nama_gelar) }} {{ strtolower($dosen->nip) }} {{ strtolower($dosen->user->username) }}'.includes(search.toLowerCase())"
                        class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-primary-container text-on-primary-container flex items-center justify-center font-black text-sm shadow-sm border border-primary/10">
                                    {{ strtoupper(substr($dosen->nama_gelar, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-black text-on-surface text-base leading-tight">{{ $dosen->nama_gelar }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest opacity-50 mt-0.5">Dosen Pembimbing</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 bg-surface-container-high text-on-surface font-mono font-black rounded-xl text-[11px] tracking-widest border border-outline-variant/10 shadow-sm">
                                {{ $dosen->nip }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-on-surface tracking-tight">@ {{ $dosen->user->username }}</span>
                                <span class="text-[11px] text-on-surface-variant font-bold opacity-60">{{ $dosen->user->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-end gap-3">
                                <button
                                    @click="
                                        editData = { id: {{ $dosen->id }}, nip: '{{ addslashes($dosen->nip) }}', nama: '{{ addslashes($dosen->nama_gelar) }}', email: '{{ addslashes($dosen->user->email) }}' };
                                        showEditModal = true;
                                    "
                                    class="px-4 py-2.5 bg-surface-container-high text-on-surface text-[11px] font-black uppercase tracking-widest rounded-xl border border-outline-variant/10 hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Edit
                                </button>
                                <button
                                    onclick="confirmDelete({{ $dosen->id }}, '{{ addslashes($dosen->nama_gelar) }}')"
                                    class="px-4 py-2.5 bg-error/5 text-error text-[11px] font-black uppercase tracking-widest rounded-xl border border-error/10 hover:bg-error hover:text-white hover:border-error transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus
                                </button>
                            </div>
                            <form id="deleteForm-{{ $dosen->id }}" action="{{ route('admin.dosen.destroy', $dosen) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-surface-container rounded-[32px] flex items-center justify-center text-outline-variant mb-6 border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-5xl">person_off</span>
                                </div>
                                <h4 class="text-xl font-black text-on-surface tracking-tight">Belum Ada Dosen</h4>
                                <p class="text-[11px] text-on-surface-variant font-black uppercase tracking-widest opacity-50 mt-2">Daftarkan dosen pembimbing baru untuk mengelola skripsi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dosens->hasPages())
        <div class="px-8 py-6 bg-surface-container-low border-t border-outline-variant/10">
            {{ $dosens->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Tambah Dosen -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-xl overflow-hidden border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="p-10">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">person_add</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface tracking-tighter">Tambah Dosen Baru</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Registrasi personel pembimbing akademik baru</p>
                    </div>
                </div>

                <form action="{{ route('admin.dosen.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">NIP Dosen</label>
                        <input type="text" name="nip" required placeholder="Contoh: 1980..."
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nama & Gelar</label>
                        <input type="text" name="nama_gelar" required placeholder="Nama lengkap..."
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Username Login</label>
                        <input type="text" name="username" required placeholder="username.dosen"
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Email Institusi</label>
                        <input type="email" name="email" required placeholder="email@umi.ac.id"
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-2" x-data="{ show: false }">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Kata Sandi</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required placeholder="Min. 8 karakter"
                                class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 flex gap-4 mt-6">
                        <button type="button" @click="showAddModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-primary text-on-primary text-sm font-black rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all hover:-translate-y-0.5">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Dosen -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-xl overflow-hidden border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="p-10">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">edit_square</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface tracking-tighter">Edit Data Dosen</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Perbarui informasi kredensial dan identitas</p>
                    </div>
                </div>

                <form :action="'/admin/dosen/' + editData.id" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">NIP Dosen</label>
                        <input type="text" name="nip" x-model="editData.nip" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nama & Gelar</label>
                        <input type="text" name="nama_gelar" x-model="editData.nama" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Email Institusi</label>
                        <input type="email" name="email" x-model="editData.email" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-2" x-data="{ show: false }">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Kata Sandi Baru <span class="opacity-40 font-bold lowercase">(opsional)</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="Kosongkan jika tidak ingin diganti"
                                class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 hover:text-primary transition-all">
                                <span class="material-symbols-outlined text-lg" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2 flex gap-4 mt-6">
                        <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-sm font-black text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-primary text-on-primary text-sm font-black rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary-lg transition-all hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, nama) {
    if (confirm('Hapus data dosen "' + nama + '"? Akun login dosen ini juga akan dihapus.')) {
        document.getElementById('deleteForm-' + id).submit();
    }
}
</script>

@endsection
