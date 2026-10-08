@extends('layouts.app')

@section('title', 'Manajemen Proyek & Unit Kavling')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: false }">

    <!-- Top Action & Filter Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div>
            <h1 class="text-xl md:text-2xl font-bold font-outfit text-white">Daftar Master Proyek</h1>
            <p class="text-xs md:text-sm text-slate-400 mt-1">
                Kelola status, lokasi tanah RBK & Non-RBK, serta unit blok kavling.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Add Project Button (Full & Partial Access) -->
            @if(auth()->user()->canEditProgress())
                <button @click="addModalOpen = true" 
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white font-semibold text-xs shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Proyek Baru</span>
                </button>
            @else
                <span class="px-3 py-2 rounded-xl bg-slate-800/80 text-slate-500 text-xs font-medium border border-slate-700 cursor-not-allowed" title="Restricted Access View Only">
                    <i class="fa-solid fa-lock text-[10px]"></i> Mode View Only (Restricted)
                </span>
            @endif
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search -->
            <div class="relative min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama proyek..." 
                       class="w-full pl-9 pr-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-sky-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-500"></i>
            </div>

            <!-- Category -->
            <select name="category" onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                <option value="">Semua Kategori Tanah</option>
                <option value="rbk_land" {{ $category === 'rbk_land' ? 'selected' : '' }}>RBK Land</option>
                <option value="non_rbk_land" {{ $category === 'non_rbk_land' ? 'selected' : '' }}>Non-RBK Land</option>
            </select>

            <!-- Status -->
            <select name="status" onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                <option value="">Semua Status Proyek</option>
                <option value="planning" {{ $status === 'planning' ? 'selected' : '' }}>Planning</option>
                <option value="on_progress" {{ $status === 'on_progress' ? 'selected' : '' }}>On Progress</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ $status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
            </select>

            @if($category || $status || $search)
                <a href="{{ route('projects.index') }}" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-xs font-medium">
                    Reset Filter
                </a>
            @endif
        </form>

        <span class="text-xs text-slate-400">Total: <strong class="text-white">{{ $projects->total() }}</strong> Proyek</span>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
            <div class="bg-slate-900/90 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 shadow-xl transition-all flex flex-col justify-between group">
                <div>
                    <!-- Category & Status Badge -->
                    <div class="flex items-center justify-between mb-3">
                        @if($project->category === 'rbk_land')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-400 border border-sky-500/30">
                                RBK LAND
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">
                                NON-RBK LAND
                            </span>
                        @endif

                        @if($project->status === 'on_progress')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">On Progress</span>
                        @elseif($project->status === 'completed')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-sky-500/10 text-sky-400 border border-sky-500/30">Completed</span>
                        @elseif($project->status === 'planning')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">Planning</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30">On Hold</span>
                        @endif
                    </div>

                    <!-- Project Title -->
                    <h3 class="text-base font-bold font-outfit text-white group-hover:text-sky-400 transition-colors line-clamp-1 mb-2">
                        <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                    </h3>

                    <p class="text-xs text-slate-400 mb-4">
                        Nilai Kontrak: <strong class="text-white">Rp {{ number_format($project->total_contract_value, 0, ',', '.') }}</strong>
                    </p>

                    <!-- Progress Info & Bar -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Realisasi: <strong class="text-emerald-400">{{ $project->actual_progress }}%</strong></span>
                            <span class="text-slate-400">Target: <strong class="text-sky-400">{{ $project->planned_progress }}%</strong></span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-sky-500 to-emerald-400" style="width: {{ min(100, max(0, $project->actual_progress)) }}%"></div>
                        </div>

                        <!-- Variance Indicator -->
                        <div class="flex justify-between items-center text-[11px] pt-1">
                            <span class="text-slate-400">Deviasi (Variance):</span>
                            @if($project->variance >= 0)
                                <span class="text-emerald-400 font-bold">+{{ $project->variance }}% (Tepat Waktu)</span>
                            @else
                                <span class="text-rose-400 font-bold">{{ $project->variance }}% (Keterlambatan)</span>
                            @endif
                        </div>
                    </div>

                    <!-- Counter Badges -->
                    <div class="grid grid-cols-3 gap-2 py-2 border-t border-b border-slate-800 text-[11px] text-center">
                        <div>
                            <span class="block text-slate-400 text-[10px]">Unit Kavling</span>
                            <strong class="text-slate-200">{{ $project->project_units_count }} Unit</strong>
                        </div>
                        <div>
                            <span class="block text-slate-400 text-[10px]">Transaksi</span>
                            <strong class="text-slate-200">{{ $project->cashflows_count }} Item</strong>
                        </div>
                        <div>
                            <span class="block text-slate-400 text-[10px]">Dokumen Hub</span>
                            <strong class="text-slate-200">{{ $project->project_documents_count }} File</strong>
                        </div>
                    </div>
                </div>

                <!-- Footer Link -->
                <div class="pt-4 mt-4 flex items-center justify-between border-t border-slate-800/80">
                    <a href="{{ route('projects.show', $project->id) }}" class="text-xs font-semibold text-sky-400 hover:text-sky-300 flex items-center gap-1.5">
                        <span>Detail & Progres Proyek</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>

                    @if(auth()->user()->isFullAccess())
                        <form method="POST" action="{{ route('projects.destroy', $project->id) }}" onsubmit="return confirm('Hapus proyek ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 p-1" title="Hapus Proyek">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center text-slate-400">
                <i class="fa-solid fa-folder-open text-4xl text-slate-600 mb-3"></i>
                <p class="text-sm font-semibold text-slate-300">Tidak ada proyek ditemukan</p>
                <p class="text-xs text-slate-500 mt-1">Coba atur ulang filter pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>

    <!-- ADD PROJECT MODAL -->
    @if(auth()->user()->canEditProgress())
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="addModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Tambah Proyek Baru</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('projects.store') }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nama Proyek</label>
                        <input type="text" name="name" required placeholder="Contoh: Perumahan RBK Modern Land" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Kategori Tanah</label>
                            <select name="category" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="rbk_land">RBK Land</option>
                                <option value="non_rbk_land">Non-RBK Land</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Status Proyek</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="planning">Planning</option>
                                <option value="on_progress" selected>On Progress</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Total Nilai Kontrak (Rp)</label>
                        <input type="number" step="100000" name="total_contract_value" required placeholder="1000000000" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Target Rencana (%)</label>
                            <input type="number" step="0.1" name="planned_progress" value="0.0" min="0" max="100" required 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Realisasi Aktual (%)</label>
                            <input type="number" step="0.1" name="actual_progress" value="0.0" min="0" max="100" required 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-semibold shadow-md shadow-sky-500/30">Simpan Proyek</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
