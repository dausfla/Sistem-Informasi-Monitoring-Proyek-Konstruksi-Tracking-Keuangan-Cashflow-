@extends('layouts.app')

@section('title', 'Modul Keuangan & Cashflow Proyek')

@section('content')
<div class="space-y-6" x-data="{ addModalOpen: false }">

    <!-- Header & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div>
            <h1 class="text-xl md:text-2xl font-bold font-outfit text-white">Modul Keuangan & Cashflow</h1>
            <p class="text-xs md:text-sm text-slate-400 mt-1">
                Pencatatan arus kas masuk (pembayaran konsumen/bank) & pengeluaran proyek (material, upah, operasional).
            </p>
        </div>

        <div>
            @if(auth()->user()->canEditFinancials())
                <button @click="addModalOpen = true" 
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold text-xs shadow-lg shadow-emerald-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Catat Transaksi Keuangan</span>
                </button>
            @else
                <span class="px-3 py-2 rounded-xl bg-slate-800/80 text-slate-500 text-xs font-medium border border-slate-700 cursor-not-allowed" title="Hanya Full Access (BoD/Finance) yang dapat mencatat transaksi">
                    <i class="fa-solid fa-lock text-[10px]"></i> Input Dibatasi (Full Access Only)
                </span>
            @endif
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
            <span class="block text-[10px] uppercase font-bold text-slate-400">Total Pemasukan (Income)</span>
            <p class="text-xl font-extrabold font-outfit text-emerald-400 mt-1">
                + Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
            <span class="block text-[10px] uppercase font-bold text-slate-400">Total Pengeluaran (Expense)</span>
            <p class="text-xl font-extrabold font-outfit text-rose-400 mt-1">
                - Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </p>
        </div>

        <div class="p-4 rounded-xl bg-slate-900 border border-slate-800">
            <span class="block text-[10px] uppercase font-bold text-slate-400">Saldo Kas Net (Balance)</span>
            <p class="text-xl font-extrabold font-outfit {{ $balance >= 0 ? 'text-emerald-400' : 'text-rose-400' }} mt-1">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 flex flex-wrap items-center justify-between gap-4">
        <form method="GET" action="{{ route('cashflows.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select name="project_id" onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 max-w-[220px] truncate">
                <option value="">Semua Proyek</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ $projectId == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>

            <select name="type" onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                <option value="">Semua Tipe Transaksi</option>
                <option value="income" {{ $type === 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                <option value="expense" {{ $type === 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
            </select>

            <select name="category" onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                <option value="">Semua Kategori</option>
                <option value="material" {{ $category === 'material' ? 'selected' : '' }}>Material</option>
                <option value="labor" {{ $category === 'labor' ? 'selected' : '' }}>Labor (Upah Workman)</option>
                <option value="operational" {{ $category === 'operational' ? 'selected' : '' }}>Operational</option>
                <option value="others" {{ $category === 'others' ? 'selected' : '' }}>Others / Lain-lain</option>
            </select>

            @if($projectId || $type || $category)
                <a href="{{ route('cashflows.index') }}" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-xs font-medium">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Master Table -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800/70 text-slate-300 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-700">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Proyek</th>
                        <th class="p-3">Tipe</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Nominal Transaksi (Rp)</th>
                        <th class="p-3">Keterangan</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-200">
                    @forelse($cashflows as $cf)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3 text-slate-400 font-medium">{{ $cf->transaction_date->format('d M Y') }}</td>
                            <td class="p-3 font-semibold text-white">{{ $cf->project ? $cf->project->name : 'N/A' }}</td>
                            <td class="p-3">
                                @if($cf->type === 'income')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">PEMASUKAN</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">PENGELUARAN</span>
                                @endif
                            </td>
                            <td class="p-3 uppercase font-semibold text-slate-300">{{ $cf->category }}</td>
                            <td class="p-3 font-bold text-sm {{ $cf->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $cf->type === 'income' ? '+' : '-' }} Rp {{ number_format($cf->amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-slate-300">{{ $cf->notes ?? '-' }}</td>
                            <td class="p-3 text-right">
                                @if(auth()->user()->canEditFinancials())
                                    <form method="POST" action="{{ route('cashflows.destroy', $cf->id) }}" onsubmit="return confirm('Hapus transaksi keuangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 p-1" title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500">Tidak ada data transaksi cashflow ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $cashflows->links() }}
        </div>
    </div>

    <!-- ADD CASHFLOW TRANSACTION MODAL -->
    @if(auth()->user()->canEditFinancials())
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="addModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Catat Transaksi Keuangan</h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('cashflows.store') }}" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Pilih Proyek Target</label>
                        <select name="project_id" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Tipe Transaksi</label>
                            <select name="type" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="income">Pemasukan (Income)</option>
                                <option value="expense" selected>Pengeluaran (Expense)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Kategori Biaya</label>
                            <select name="category" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="material">Material (Semen, Besi, Bata)</option>
                                <option value="labor">Labor (Upah Tukang & Subkon)</option>
                                <option value="operational">Operational (Perizinan, Listrik, Sewa)</option>
                                <option value="others">Others / Lain-lain</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Nominal Transaksi (Rp)</label>
                            <input type="number" step="1000" name="amount" required placeholder="5000000" 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Keterangan / Catatan Transaksi</label>
                        <textarea name="notes" rows="2" placeholder="Catatan detail rincian transaksi..." 
                                  class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
