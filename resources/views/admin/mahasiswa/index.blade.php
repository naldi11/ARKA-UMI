@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa')

@section('content')

<div class="space-y-8" x-data="{ 
    search: '', 
    showAddModal: false, 
    showEditModal: false,
    editData: { id: null, nim: '', nama: '', email: '', status: '' }
}">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Data Mahasiswa</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Manajemen Mahasiswa</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Kelola seluruh basis data mahasiswa. Anda dapat memperbarui informasi akademik, status akun, atau menghapus record mahasiswa.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" 
                       x-model="search"
                       placeholder="Cari NIM atau Nama..." 
                       class="pl-12 pr-6 py-3 bg-surface-container-high text-on-surface rounded-2xl font-bold text-sm transition-all focus:bg-white focus:ring-4 focus:ring-primary/10 border border-outline-variant/10 outline-none w-64 shadow-sm">
            </div>
            <button @click="showAddModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl font-black text-sm shadow-premium hover:shadow-premium-lg transition-all hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[20px]">group_add</span>
                Tambah Mahasiswa
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
            <h3 class="text-[11px] font-black text-on-surface uppercase tracking-[0.2em]">Database Mahasiswa</h3>
            <div class="flex items-center gap-4">
                <span class="text-[11px] font-black text-on-surface-variant uppercase tracking-widest opacity-60">{{ $students->total() }} Mahasiswa Terdaftar</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-8 py-4">Nama Mahasiswa</th>
                        <th class="px-8 py-4">Nomor Induk (NIM)</th>
                        <th class="px-8 py-4">Email & Akun</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($students as $student)
                    <tr x-show="'{{ strtolower($student->user->name) }} {{ strtolower($student->nim) }} {{ strtolower($student->user->username) }}'.includes(search.toLowerCase())"
                        class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-surface-container-high text-primary flex items-center justify-center font-black text-sm shadow-sm border border-outline-variant/10">
                                    {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-black text-on-surface text-base leading-tight">{{ $student->user->name }}</div>
                                    <div class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest opacity-50 mt-0.5">Program Studi Akuntansi</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 bg-surface-container-high text-on-surface font-mono font-black rounded-xl text-[11px] tracking-widest border border-outline-variant/10 shadow-sm">
                                {{ $student->nim }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-on-surface tracking-tight">@ {{ $student->user->username }}</span>
                                <span class="text-[11px] text-on-surface-variant font-bold opacity-60">{{ $student->user->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $statusClass = match($student->user->status) {
                                    'active'   => 'bg-success/10 text-success border-success/20',
                                    'pending'  => 'bg-warning/10 text-warning border-warning/20',
                                    'rejected' => 'bg-error/10 text-error border-error/20',
                                    default    => 'bg-surface-container-high text-on-surface-variant border-outline-variant/10'
                                };
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                {{ $student->user->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-end gap-3">
                                <button
                                    @click="
                                        editData = { id: {{ $student->id }}, nim: '{{ $student->nim }}', nama: '{{ addslashes($student->user->name) }}', email: '{{ addslashes($student->user->email) }}', status: '{{ $student->user->status }}' };
                                        showEditModal = true;
                                    "
                                    class="px-4 py-2.5 bg-surface-container-high text-on-surface text-[11px] font-black uppercase tracking-widest rounded-xl border border-outline-variant/10 hover:bg-primary hover:text-white hover:border-primary transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Edit
                                </button>
                                <button
                                    onclick="confirmDeleteStudent({{ $student->id }}, '{{ addslashes($student->user->name) }}')"
                                    class="px-4 py-2.5 bg-error/5 text-error text-[11px] font-black uppercase tracking-widest rounded-xl border border-error/10 hover:bg-error hover:text-white hover:border-error transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus
                                </button>
                            </div>
                            <form id="deleteStudentForm-{{ $student->id }}" action="{{ route('admin.mahasiswa.destroy', $student) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-surface-container rounded-[32px] flex items-center justify-center text-on-surface-variant/20 mb-6 border border-outline-variant/5">
                                    <span class="material-symbols-outlined text-5xl">group_off</span>
                                </div>
                                <h4 class="text-xl font-black text-on-surface tracking-tight">Belum Ada Mahasiswa</h4>
                                <p class="text-[11px] text-on-surface-variant font-black uppercase tracking-widest opacity-50 mt-2">Database mahasiswa masih kosong saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="px-8 py-6 bg-surface-container-low border-t border-outline-variant/10">
            {{ $students->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Tambah Mahasiswa -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-xl overflow-hidden border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="p-10">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">person_add</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface tracking-tighter">Tambah Mahasiswa</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Input manual data mahasiswa baru</p>
                    </div>
                </div>

                <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nomor Induk (NIM)</label>
                        <input type="text" name="nim" required placeholder="Contoh: 2021..."
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nama Mahasiswa</label>
                        <input type="text" name="name" required placeholder="Nama lengkap..."
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Username</label>
                        <input type="text" name="username" required placeholder="username.mhs"
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Email</label>
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
                            Simpan Mahasiswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Mahasiswa -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-[400] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-[32px] shadow-premium-lg w-full max-w-xl overflow-hidden border border-outline-variant/10 animate-in zoom-in-95 duration-200">
            <div class="p-10">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/10 shadow-sm">
                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1;">manage_accounts</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface tracking-tighter">Edit Data Mahasiswa</h3>
                        <p class="text-sm text-on-surface-variant font-medium">Perbarui status dan informasi akademik</p>
                    </div>
                </div>

                <form :action="'/admin/mahasiswa/' + editData.id" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">NIM</label>
                        <input type="text" name="nim" x-model="editData.nim" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Nama Mahasiswa</label>
                        <input type="text" name="name" x-model="editData.nama" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Email</label>
                        <input type="email" name="email" x-model="editData.email" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Status Akun</label>
                        <select name="status" x-model="editData.status" required
                            class="w-full px-5 py-4 bg-surface-container-low border border-transparent rounded-2xl text-sm font-bold focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all outline-none appearance-none">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="space-y-2 md:col-span-2" x-data="{ show: false }">
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-widest px-1">Kata Sandi Baru <span class="opacity-40 font-bold lowercase">(opsional)</span></label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" placeholder="Kosongkan jika tidak diganti"
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
function confirmDeleteStudent(id, nama) {
    if (confirm('Hapus data mahasiswa "' + nama + '" secara permanen?')) {
        document.getElementById('deleteStudentForm-' + id).submit();
    }
}
</script>

@endsection
