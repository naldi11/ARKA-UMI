@php
    $role = Auth::user()->role;
    $navs = [];

    if ($role === 'admin') {
        $navs = [
            ['label' => 'Dashboard',           'icon' => 'dashboard',       'route' => 'admin.dashboard'],
            ['label' => 'Verifikasi Akun',      'icon' => 'how_to_reg',      'route' => 'admin.mahasiswa.pending'],
            ['label' => 'Data Mahasiswa',       'icon' => 'group',           'route' => 'admin.mahasiswa.index'],
            ['label' => 'Konfirmasi Judul',     'icon' => 'fact_check',      'route' => 'admin.title-proposals.index'],
            ['label' => 'Kelola Dosen',         'icon' => 'school',          'route' => 'admin.dosen.index'],
            ['label' => 'Data Skripsi',         'icon' => 'menu_book',       'route' => 'admin.theses.index'],
            ['label' => 'Akses Angkatan',       'icon' => 'event_available', 'route' => 'admin.angkatan.index'],
            ['label' => 'Log Aktivitas',        'icon' => 'history',         'route' => 'admin.activity.index'],
            ['label' => 'Arsip & Ekspor',       'icon' => 'archive',         'route' => 'admin.archive'],
        ];
    } elseif ($role === 'dosen') {
        $navs = [
            ['label' => 'Dashboard',            'icon' => 'dashboard',       'route' => 'dosen.dashboard'],
            ['label' => 'Mahasiswa Bimbingan',  'icon' => 'clinical_notes',  'route' => 'dosen.theses.index'],
        ];
    } elseif ($role === 'mahasiswa') {
        $thesis = Auth::user()->mahasiswa->thesis;
        $batch1Done = $thesis ? $thesis->isBatch1Complete() : false;
        $batch2Done = $thesis ? ($thesis->doc_jurnal && $thesis->doc_skripsi) : false;

        $navs = [
            ['label' => 'Dashboard',            'icon' => 'dashboard',       'route' => 'mahasiswa.dashboard'],
            ['label' => 'Profil Saya',          'icon' => 'account_circle',  'route' => 'mahasiswa.profile'],
            ['label' => 'Status Skripsi',       'icon' => 'description',     'route' => 'mahasiswa.thesis.status'],
            
            // Batch 1: Administrasi (Always active if thesis exists)
            ['label' => '1. Berkas Administrasi', 'icon' => 'assignment_ind', 'route' => 'mahasiswa.upload.administrasi.page', 'disabled' => !$thesis],
            
            // Batch 2: Penelitian (Active if Batch 1 done)
            ['label' => '2. Berkas Penelitian',  'icon' => 'article',        'route' => 'mahasiswa.upload.penelitian.page',   'disabled' => !$batch1Done],
            
            // Batch 3: Kelulusan (Active if Batch 2 done)
            ['label' => '3. Berkas Kelulusan',   'icon' => 'verified',       'route' => 'mahasiswa.upload.kelulusan.page',    'disabled' => !$batch2Done],
        ];
    }
@endphp

@foreach($navs as $nav)
    @php 
        $isDisabled = $nav['disabled'] ?? false; 
        $isActive = request()->routeIs($nav['route']);
    @endphp
    <a href="{{ $isDisabled ? '#' : (Route::has($nav['route']) ? route($nav['route']) : '#') }}"
       class="group relative flex items-center gap-4 px-5 py-4 rounded-2xl mb-2 transition-all duration-300
              {{ $isDisabled ? 'opacity-30 cursor-not-allowed grayscale' : '' }}
              {{ $isActive
                 ? 'bg-gradient-to-r from-primary to-accent-indigo text-white font-black shadow-xl shadow-primary/20 scale-[1.02]'
                 : 'text-on-surface-variant font-bold hover:bg-surface-container-high hover:text-on-surface' }}">
        
        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300
             {{ $isActive ? 'bg-white/20' : 'bg-surface-container text-on-surface-variant/40 group-hover:bg-white group-hover:text-primary group-hover:shadow-sm group-hover:scale-110' }}">
            <span class="material-symbols-outlined text-[24px] {{ $isActive ? 'text-white' : '' }}">{{ $nav['icon'] }}</span>
        </div>

        <span class="tracking-tight text-sm flex-1">{{ $nav['label'] }}</span>
        
        @if($isDisabled)
            <span class="material-symbols-outlined text-xs opacity-50">lock</span>
        @elseif($isActive)
            <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        @endif
    </a>
@endforeach
