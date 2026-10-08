@extends('layouts.app')

@section('title', 'Document Hub - RAB, DED, Invoice & Legal')

@section('content')
<div class="space-y-6" x-data="{ uploadModalOpen: false }">

    <!-- Header Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 rounded-2xl p-5 shadow-xl">
        <div>
            <h1 class="text-xl md:text-2xl font-bold font-outfit text-white">Document Hub Proyek</h1>
            <p class="text-xs md:text-sm text-slate-400 mt-1">
                Penyimpanan & verifikasi status dokumen resmi (RAB, DED, Kurva S, Invoice, Kwitansi, SP, PKS, PPJB, BAST).
            </p>
        </div>

        <div>
            @if(auth()->user()->canEditProgress())
                <button @click="uploadModalOpen = true" 
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white font-semibold text-xs shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <span>Unggah Dokumen Baru</span>
                </button>
            @else
                <span class="px-3 py-2 rounded-xl bg-slate-800/80 text-slate-500 text-xs font-medium border border-slate-700 cursor-not-allowed">
                    <i class="fa-solid fa-lock text-[10px]"></i> Mode View Only (Dokumen Disetujui)
                </span>
            @endif
        </div>
    </div>

    <!-- Type Pills & Filter Bar -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 space-y-3">
        <!-- Type Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-800 pb-3">
            <span class="text-[10px] uppercase font-bold text-slate-400 mr-2">Tipe Dokumen:</span>
            <a href="{{ route('documents.index', array_merge(request()->query(), ['type' => ''])) }}" 
               class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-all {{ empty($type) ? 'bg-sky-500 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                Semua Tipe
            </a>
            @foreach(['rab', 'ded', 'kurva_s', 'invoice', 'kwitansi', 'sp', 'pks', 'ppjb', 'bast'] as $t)
                <a href="{{ route('documents.index', array_merge(request()->query(), ['type' => $t])) }}" 
                   class="px-2.5 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all {{ $type === $t ? 'bg-sky-500 text-white shadow-md' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    {{ strtoupper($t) }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('documents.index') }}" class="flex flex-wrap items-center justify-between gap-3">
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="flex items-center gap-3">
                <select name="project_id" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 max-w-[220px] truncate">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}" {{ $projectId == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                    <option value="">Semua Status Dokumen</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending_payment" {{ $status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                </select>

                @if($projectId || $status || $type)
                    <a href="{{ route('documents.index') }}" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white text-xs font-medium">
                        Reset Filter
                    </a>
                @endif
            </div>

            <span class="text-xs text-slate-400">Total: <strong class="text-white">{{ $documents->total() }}</strong> Dokumen</span>
        </form>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $doc)
            <div class="bg-slate-900/90 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 shadow-xl transition-all flex flex-col justify-between group">
                <div>
                    <!-- Type Badge & Status Badge -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-sky-500/10 text-sky-400 border border-sky-500/30">
                            {{ strtoupper($doc->type) }}
                        </span>

                        @if($doc->status === 'approved')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">APPROVED</span>
                        @elseif($doc->status === 'paid')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/30">PAID</span>
                        @elseif($doc->status === 'pending_payment')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">PENDING PAYMENT</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">DRAFT</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h3 class="text-sm font-bold font-outfit text-white group-hover:text-sky-400 transition-colors line-clamp-2 mb-2">
                        {{ $doc->title }}
                    </h3>

                    <p class="text-xs text-slate-400 mb-4 flex items-center gap-1.5">
                        <i class="fa-solid fa-building text-[10px]"></i>
                        <span class="truncate">{{ $doc->project ? $doc->project->name : 'N/A' }}</span>
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-800 space-y-3">
                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                        <span>Oleh: <strong class="text-slate-200">{{ $doc->uploader->name ?? 'System' }}</strong></span>
                        <span>{{ $doc->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-2 pt-1">
                        <a href="{{ route('documents.download', $doc->id) }}" 
                           class="w-full text-center px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-sky-400 font-semibold text-xs border border-slate-700 transition-colors flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-download"></i>
                            <span>Unduh File</span>
                        </a>

                        @if(auth()->user()->isFullAccess() && $doc->status === 'draft')
                            <form method="POST" action="{{ route('documents.updateStatus', $doc->id) }}" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-3 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/40 text-xs font-semibold transition-colors">
                                    Approve
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center text-slate-400">
                <i class="fa-solid fa-folder-closed text-4xl text-slate-600 mb-3"></i>
                <p class="text-sm font-semibold text-slate-300">Belum ada dokumen yang sesuai dengan filter.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>

    <!-- UPLOAD DOCUMENT MODAL -->
    @if(auth()->user()->canEditProgress())
        <div x-show="uploadModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="uploadModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="text-base font-bold font-outfit text-white">Unggah Dokumen Proyek</h3>
                    <button @click="uploadModalOpen = false" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Pilih Proyek</label>
                        <select name="project_id" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
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
                            <label class="block font-semibold text-slate-300 mb-1">Status Awal</label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                                <option value="draft">Draft</option>
                                @if(auth()->user()->isFullAccess())
                                    <option value="approved">Approved</option>
                                    <option value="pending_payment">Pending Payment</option>
                                    <option value="paid">Paid (Lunas)</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">Judul Dokumen Resmi</label>
                        <input type="text" name="title" required placeholder="Contoh: RAB Bangunan Gedung Utama 2026" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none focus:border-sky-500">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-300 mb-1">File Dokumen (PDF, Gambar, Word, Excel)</label>
                        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xlsx" 
                               class="w-full px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-white focus:outline-none">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800">
                        <button type="button" @click="uploadModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-semibold">Unggah File</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
