@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')

<div class="space-y-8">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-black text-on-surface-variant mb-3 uppercase tracking-[0.2em]">
                <span>Admin</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-black">Log Aktivitas</span>
            </nav>
            <h1 class="text-4xl font-black tracking-tighter text-on-surface mb-2">Audit Trail</h1>
            <p class="text-on-surface-variant font-medium max-w-2xl leading-relaxed">Pantau seluruh aktivitas administrator dan perubahan sistem secara real-time untuk transparansi dan keamanan data.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.activity.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh log aktivitas?')">
                @csrf
                <button type="submit" class="px-6 py-3 bg-surface-container-high text-error text-xs font-black uppercase tracking-widest rounded-2xl border border-error/10 hover:bg-error hover:text-white transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                    Bersihkan Log
                </button>
            </form>
        </div>
    </header>

    @if(session('status'))
        <div class="p-5 bg-success/5 text-success border border-success/10 rounded-2xl text-xs font-black flex items-center gap-3 shadow-sm mb-6 animate-in fade-in slide-in-from-top-4">
            <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('status') }}
        </div>
    @endif

    <!-- Activities Table -->
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant border-b border-outline-variant/5">
                        <th class="px-8 py-4">Waktu</th>
                        <th class="px-8 py-4">Administrator</th>
                        <th class="px-8 py-4">Aksi</th>
                        <th class="px-8 py-4">Deskripsi</th>
                        <th class="px-8 py-4 text-right">Perangkat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="text-sm font-black text-on-surface">{{ $activity->created_at->format('H:i:s') }}</div>
                            <div class="text-[10px] text-on-surface-variant font-bold opacity-50">{{ $activity->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">
                                    {{ strtoupper(substr($activity->user->name ?? 'A', 0, 2)) }}
                                </div>
                                <div class="text-sm font-bold text-on-surface">{{ $activity->user->name ?? 'System' }}</div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-2.5 py-1 bg-surface-container-high text-on-surface font-black rounded-lg text-[10px] tracking-widest border border-outline-variant/10 uppercase">
                                {{ str_replace('_', ' ', $activity->action) }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm text-on-surface-variant font-medium leading-relaxed">{{ $activity->description }}</p>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="text-[10px] text-on-surface-variant font-bold opacity-40 uppercase tracking-widest" title="{{ $activity->user_agent }}">
                                {{ $activity->ip_address }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-surface-container rounded-3xl flex items-center justify-center text-outline-variant mb-4">
                                    <span class="material-symbols-outlined text-4xl">history</span>
                                </div>
                                <p class="text-on-surface-variant font-black">Belum ada aktivitas tercatat.</p>
                                <p class="text-[11px] text-on-surface-variant opacity-50 mt-1 uppercase tracking-widest">Sistem akan mencatat setiap tindakan administratif secara otomatis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
        <div class="px-8 py-6 bg-surface-container-low border-t border-outline-variant/10">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
