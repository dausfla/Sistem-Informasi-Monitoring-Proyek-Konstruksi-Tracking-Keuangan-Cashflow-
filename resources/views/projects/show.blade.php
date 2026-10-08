@extends('layouts.app')

@section('title', 'Detail Proyek - ' . $project->name)

@section('content')
<div class="space-y-6" x-data="{ editModalOpen: false, addUnitModalOpen: false, uploadDocModalOpen: false }">

    <!-- Project Header & Summary Card -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('projects.index') }}" class="text-xs text-sky-400 hover:text-sky-300 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar Proyek
                    </a>
                    <span class="text-slate-600">•</span>
                    @if($project->category === 'rbk_land')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-500/10 text-sky-400 border border-sky-500/30">RBK LAND</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">NON-RBK LAND</span>
                    @endif
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold font-outfit text-white">{{ $project->name }}</h1>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if(auth()->user()->canEditProgress())
                    <button @click="editModalOpen = true" 
                            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sky-400 text-xs font-semibold border border-slate-700 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Update Progres & Detail
                    </button>
                    <button @click="addUnitModalOpen = true" 
                            class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-400 text-xs font-semibold border border-slate-700 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-square-plus"></i> Tambah Unit/Blok
                    </button>
                @endif
                @if(auth()->user()->canEditProgress())
                    <button @click="uploadDocModalOpen = true" 
                            class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-arrow-up"></i> Unggah Dokumen
                    </button>
                @endif
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-2">
            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Nilai Kontrak</span>
                <strong class="text-base md:text-lg font-outfit text-white">Rp {{ number_format($project->total_contract_value, 0, ',', '.') }}</strong>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Progres Target Rencana</span>
                <strong class="text-base md:text-lg font-outfit text-sky-400">{{ $project->planned_progress }}%</strong>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Progres Realisasi Aktual</span>
                <strong class="text-base md:text-lg font-outfit text-emerald-400">{{ $project->actual_progress }}%</strong>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-800/60 border border-slate-700/60">
                <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">Deviasi (Variance)</span>
                @if($project->variance >= 0)
                    <strong class="text-base md:text-lg font-outfit text-emerald-400">+{{ $project->variance }}% (Ahead)</strong>
                @else
                    <strong class="text-base md:text-lg font-outfit text-rose-400">{{ $project->variance }}% (Behind)</strong>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="space-y-1">
            <div class="flex justify-between text-xs text-slate-400">
                <span>Penyelesaian Fisik Lapangan</span>
                <span class="text-emerald-400 font-bold">{{ $project->actual_progress }}%</span>
            </div>
            <div class="w-full h-3 rounded-full bg-slate-800 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-sky-500 via-emerald-400 to-emerald-500" style="width: {{ min(100, max(0, $project->actual_progress)) }}%"></div>
            </div>
        </div>
    </div>

    <!-- 2. PROJECT UNITS & BLOCKS TABLE -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                <i class="fa-solid fa-cubes text-sky-400"></i>
                <span>Daftar Unit Kavling / Blok Proyek ({{ $project->projectUnits->count() }} Unit)</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800/70 text-slate-300 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-700">
                        <th class="p-3">#</th>
                        <th class="p-3">Nama Blok / Zona</th>
                        <th class="p-3">Nomor Unit Kavling</th>
                        <th class="p-3">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-200">
                    @forelse($project->projectUnits as $index => $unit)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3 text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-slate-100">{{ $unit->block_name }}</td>
                            <td class="p-3 font-semibold text-sky-400">{{ $unit->unit_number }}</td>
                            <td class="p-3 text-slate-400">{{ $unit->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-500">Belum ada unit kavling terdaftar untuk proyek ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. FINANCIAL CASHFLOW RECORDS TABLE -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-emerald-400"></i>
                    <span>Catatan Cashflow Proyek</span>
                </h3>
                <p class="text-xs text-slate-400">Pemasukan & pengeluaran yang terhubung dengan proyek ini</p>
            </div>
            <div class="text-xs text-right">
                <span class="text-slate-400">Net Balance:</span>
                <strong class="{{ $project->balance >= 0 ? 'text-emerald-400' : 'text-rose-400' }} text-sm font-outfit block">
                    Rp {{ number_format($project->balance, 0, ',', '.') }}
                </strong>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800/70 text-slate-300 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-700">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Tipe</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Nominal (Rp)</th>
                        <th class="p-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-200">
                    @forelse($project->cashflows as $cf)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3 text-slate-400">{{ $cf->transaction_date->format('d M Y') }}</td>
                            <td class="p-3">
                                @if($cf->type === 'income')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">PEMASUKAN</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">PENGELUARAN</span>
                                @endif
                            </td>
                            <td class="p-3 uppercase font-semibold text-slate-300">{{ $cf->category }}</td>
                            <td class="p-3 font-bold {{ $cf->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $cf->type === 'income' ? '+' : '-' }} Rp {{ number_format($cf->amount, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-slate-300">{{ $cf->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">Belum ada transaksi cashflow terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. PROJECT DOCUMENTS HUB -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                    <i class="fa-solid fa-folder-closed text-sky-400"></i>
                    <span>Hub Dokumen Proyek (RAB, DED, Invoice, SP, BAST, dll)</span>
                </h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800/70 text-slate-300 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-700">
                        <th class="p-3">Jenis Dokumen</th>
                        <th class="p-3">Judul Dokumen</th>
                        <th class="p-3">Status Dokumen</th>
                        <th class="p-3">Pengunggah</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-200">
                    @forelse($project->projectDocuments as $doc)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3 font-bold uppercase text-sky-400">{{ $doc->type }}</td>
                            <td class="p-3 font-semibold text-white">{{ $doc->title }}</td>
                            <td class="p-3">
                                @if($doc->status === 'approved')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">APPROVED</span>
                                @elseif($doc->status === 'paid')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/30">PAID</span>
                                @elseif($doc->status === 'pending_payment')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">PENDING PAYMENT</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-700 text-slate-400">DRAFT</span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-300">{{ $doc->uploader->name ?? 'System' }}</td>
                            <td class="p-3 text-right space-x-2">
                                <a href="{{ route('documents.download', $doc->id) }}" class="text-sky-400 hover:text-sky-300 font-semibold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i> Unduh
                                </a>
                                @if(auth()->user()->isFullAccess() && $doc->status !== 'approved')
                                    <form method="POST" action="{{ route('documents.updateStatus', $doc->id) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 font-semibold text-[11px] ml-2">
                                            <i class="fa-solid fa-check-double"></i> Setuju (Approve)
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">Belum ada dokumen proyek diunggah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: EDIT PROJECT & PROGRESS -->
    @if(auth()->user()->canEditProgress())
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="editModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Update Progres & Detail Proyek</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nama Proyek</label>
                        <input type="text" name="name" value="{{ $project->name }}" required 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Kategori Tanah</label>
                            <select name="category" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="rbk_land" {{ $project->category === 'rbk_land' ? 'selected' : '' }}>RBK Land</option>
                                <option value="non_rbk_land" {{ $project->category === 'non_rbk_land' ? 'selected' : '' }}>Non-RBK Land</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Status Proyek</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>Planning</option>
                                <option value="on_progress" {{ $project->status === 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Total Nilai Kontrak (Rp)</label>
                        <input type="number" step="100000" name="total_contract_value" value="{{ $project->total_contract_value }}" required 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Target Rencana (%)</label>
                            <input type="number" step="0.1" name="planned_progress" value="{{ $project->planned_progress }}" min="0" max="100" required 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-300 mb-1">Realisasi Aktual (%)</label>
                            <input type="number" step="0.1" name="actual_progress" value="{{ $project->actual_progress }}" min="0" max="100" required 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-semibold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: ADD UNIT / BLOK -->
    @if(auth()->user()->canEditProgress())
        <div x-show="addUnitModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="addUnitModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Tambah Unit Kavling / Blok</h3>
                    <button @click="addUnitModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('projects.units.store', $project->id) }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nama Blok / Zona</label>
                        <input type="text" name="block_name" required placeholder="Contoh: Blok A" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Nomor Unit Kavling</label>
                        <input type="text" name="unit_number" required placeholder="Contoh: No. 12" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="addUnitModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 3: UPLOAD DOCUMENT -->
    @if(auth()->user()->canEditProgress())
        <div x-show="uploadDocModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="uploadDocModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Unggah Dokumen Proyek</h3>
                    <button @click="uploadDocModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Jenis Dokumen</label>
                        <select name="type" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            <option value="rab">RAB (Rencana Anggaran Biaya)</option>
                            <option value="ded">DED (Detail Engineering Design)</option>
                            <option value="kurva_s">Kurva S Progres</option>
                            <option value="invoice">Invoice Tagihan</option>
                            <option value="kwitansi">Kwitansi Pembayaran</option>
                            <option value="sp">SP (Surat Perintah Kerja)</option>
                            <option value="pks">PKS (Perjanjian Kerja Sama)</option>
                            <option value="ppjb">PPJB (Perjanjian Pengikatan Jual Beli)</option>
                            <option value="bast">BAST (Berita Acara Serah Terima)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Judul Dokumen</label>
                        <input type="text" name="title" required placeholder="Judul dokumen resmi..." 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">File Dokumen (PDF / Gambar)</label>
                        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Status Awal Dokumen</label>
                        <select name="status" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            <option value="draft">Draft (Draf)</option>
                            @if(auth()->user()->isFullAccess())
                                <option value="approved">Approved (Disetujui)</option>
                                <option value="pending_payment">Pending Payment</option>
                                <option value="paid">Paid (Lunas)</option>
                            @endif
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="uploadDocModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-semibold">Unggah File</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
