@extends('layouts.app')

@section('title', 'Dashboard Monitoring Proyek & Keuangan')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 rounded-2xl p-4 md:p-6 shadow-xl">
        <div>
            <h1 class="text-xl md:text-2xl font-bold font-outfit text-white">Ringkasan Monitoring Proyek</h1>
            <p class="text-xs md:text-sm text-slate-400 mt-1">
                Data real-time fisik & keuangan tanah <span class="text-sky-400 font-semibold">RBK Land</span> dan <span class="text-indigo-400 font-semibold">Non-RBK Land</span>.
            </p>
        </div>

        <!-- Filter Dropdown Form -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-3">
            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Kategori Tanah</label>
                <select name="category" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500">
                    <option value="">Semua Kategori Tanah</option>
                    <option value="rbk_land" {{ $selectedCategory === 'rbk_land' ? 'selected' : '' }}>RBK Land Only</option>
                    <option value="non_rbk_land" {{ $selectedCategory === 'non_rbk_land' ? 'selected' : '' }}>Non-RBK Land Only</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Pilih Proyek</label>
                <select name="project_id" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:outline-none focus:border-sky-500 max-w-[200px] truncate">
                    <option value="">Semua Proyek Aktif</option>
                    @foreach($allProjectsList as $proj)
                        <option value="{{ $proj->id }}" {{ $selectedProjectId == $proj->id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedCategory || $selectedProjectId)
                <div class="self-end">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium transition-colors inline-block">
                        Reset Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- 1. OVERVIEW METRICS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <!-- Metric 1: Total Proyek -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/60 border border-slate-800 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-3 -bottom-3 text-slate-800/40 text-6xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div class="flex items-center gap-3 text-sky-400 mb-2">
                <div class="w-8 h-8 rounded-lg bg-sky-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Proyek</span>
            </div>
            <p class="text-2xl md:text-3xl font-extrabold font-outfit text-white">{{ $totalProjects }} <span class="text-xs font-normal text-slate-400">Lokasi</span></p>
            <p class="text-[11px] text-slate-400 mt-2">
                RBK Land: <span class="text-white font-semibold">{{ $projects->where('category', 'rbk_land')->count() }}</span> | Non-RBK: <span class="text-white font-semibold">{{ $projects->where('category', 'non_rbk_land')->count() }}</span>
            </p>
        </div>

        <!-- Metric 2: Total Contract Value -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/60 border border-slate-800 shadow-xl relative overflow-hidden group">
            <div class="absolute -right-3 -bottom-3 text-slate-800/40 text-6xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div class="flex items-center gap-3 text-indigo-400 mb-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill-wave text-sm"></i>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nilai Kontrak Total</span>
            </div>
            <p class="text-2xl md:text-3xl font-extrabold font-outfit text-white">Rp {{ number_format($totalContractValue / 1000000000, 2) }} <span class="text-xs font-medium text-indigo-300">Miliar</span></p>
            <p class="text-[11px] text-slate-400 mt-2">
                Nilai akumulasi kontrak proyek terdaftar
            </p>
        </div>

        <!-- Metric 3: Rata-Rata Progres & Deviasi (Variance) -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/60 border border-slate-800 shadow-xl relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3 text-emerald-400">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Progres Fisik Rata-rata</span>
                </div>
                
                <!-- Variance Badge -->
                @if($avgVariance >= 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        <i class="fa-solid fa-arrow-trend-up"></i> +{{ $avgVariance }}% (Surplus)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                        <i class="fa-solid fa-arrow-trend-down"></i> {{ $avgVariance }}% (Deviasi)
                    </span>
                @endif
            </div>

            <div class="flex items-baseline justify-between mt-1">
                <p class="text-2xl md:text-3xl font-extrabold font-outfit text-white">{{ $avgActual }}% <span class="text-xs font-normal text-slate-400">Realisasi</span></p>
                <span class="text-xs text-slate-400">Rencana: <strong class="text-slate-200">{{ $avgPlanned }}%</strong></span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full h-2 rounded-full bg-slate-800 mt-3 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-sky-500 to-emerald-400 transition-all duration-500" style="width: {{ min(100, max(0, $avgActual)) }}%"></div>
            </div>
        </div>

        <!-- Metric 4: Keuangan Cashflow Net Balance -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-900/60 border border-slate-800 shadow-xl relative overflow-hidden group">
            <div class="flex items-center gap-3 text-amber-400 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                    <i class="fa-solid fa-scale-balanced text-sm"></i>
                </div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Saldo Kas (Net Cashflow)</span>
            </div>
            <p class="text-2xl md:text-3xl font-extrabold font-outfit {{ $netBalance >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($netBalance / 1000000, 1) }} <span class="text-xs font-medium text-slate-300">Juta</span>
            </p>
            <div class="flex items-center justify-between text-[11px] text-slate-400 mt-2">
                <span>Pemasukan: <strong class="text-emerald-400">Rp {{ number_format($totalIncome / 1000000, 0) }}M</strong></span>
                <span>Pengeluaran: <strong class="text-rose-400">Rp {{ number_format($totalExpense / 1000000, 0) }}M</strong></span>
            </div>
        </div>

    </div>

    <!-- 2. FINANCIAL MODULE & KURVA S VISUALIZATIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Kurva S / Progress Comparison Chart (8 cols) -->
        <div class="lg:col-span-8 bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-sky-400"></i>
                        <span>Progres Fisik Kurva S (Rencana vs Realisasi)</span>
                    </h3>
                    <p class="text-xs text-slate-400">Perbandingan persentase penyelesaian fisik setiap proyek</p>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="h-64 md:h-72">
                <canvas id="kurvaSChart"></canvas>
            </div>
        </div>

        <!-- Financial Breakdown Chart (4 cols) -->
        <div class="lg:col-span-4 bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2 mb-1">
                    <i class="fa-solid fa-pie-chart text-indigo-400"></i>
                    <span>Breakdown Pengeluaran Cashflow</span>
                </h3>
                <p class="text-xs text-slate-400 mb-4">Pengeluaran berdasarkan kategori operasional</p>

                <div class="h-52 relative flex items-center justify-center">
                    <canvas id="cashflowCategoryChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs pt-4 border-t border-slate-800">
                <div class="p-2 rounded-lg bg-slate-800/60">
                    <span class="block text-[10px] text-slate-400">Material</span>
                    <strong class="text-sky-400">Rp {{ number_format($expensesByCategory['material'] / 1000000, 1) }}M</strong>
                </div>
                <div class="p-2 rounded-lg bg-slate-800/60">
                    <span class="block text-[10px] text-slate-400">Labor / Upah</span>
                    <strong class="text-emerald-400">Rp {{ number_format($expensesByCategory['labor'] / 1000000, 1) }}M</strong>
                </div>
                <div class="p-2 rounded-lg bg-slate-800/60">
                    <span class="block text-[10px] text-slate-400">Operasional</span>
                    <strong class="text-amber-400">Rp {{ number_format($expensesByCategory['operational'] / 1000000, 1) }}M</strong>
                </div>
                <div class="p-2 rounded-lg bg-slate-800/60">
                    <span class="block text-[10px] text-slate-400">Lain-lain</span>
                    <strong class="text-indigo-400">Rp {{ number_format($expensesByCategory['others'] / 1000000, 1) }}M</strong>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. MATERIAL WEIGHT COMPLETION TABLE (KURVA S DETAIL) -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                    <i class="fa-solid fa-bars-staggered text-emerald-400"></i>
                    <span>Tabel Bobot Pekerjaan & Progres Kurva S</span>
                </h3>
                <p class="text-xs text-slate-400">Rincian pembobotan tahap konstruksi proyek utama</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-800/70 text-slate-300 uppercase font-semibold text-[10px] tracking-wider border-b border-slate-700">
                        <th class="p-3">Tahapan Pekerjaan</th>
                        <th class="p-3">Bobot (%)</th>
                        <th class="p-3">Target Rencana (%)</th>
                        <th class="p-3">Realisasi Aktual (%)</th>
                        <th class="p-3">Deviasi</th>
                        <th class="p-3">Status Tahap</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-200">
                    @foreach($materialWeights as $item)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="p-3 font-semibold text-slate-100">{{ $item['stage'] }}</td>
                            <td class="p-3 font-medium text-sky-400">{{ number_format($item['weight'], 1) }}%</td>
                            <td class="p-3 text-slate-300">{{ number_format($item['planned'], 1) }}%</td>
                            <td class="p-3 font-semibold text-emerald-400">{{ number_format($item['actual'], 1) }}%</td>
                            <td class="p-3">
                                @php $dev = round($item['actual'] - $item['planned'], 1); @endphp
                                @if($dev >= 0)
                                    <span class="text-emerald-400 font-bold">+{{ $dev }}%</span>
                                @else
                                    <span class="text-rose-400 font-bold">{{ $dev }}%</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($item['status'] === 'Selesai')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Selesai</span>
                                @elseif($item['status'] === 'Progres')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">Dalam Progres</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">Belum Mulai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. RECENT DOCUMENTS HUB HIGHLIGHT -->
    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-5 md:p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold font-outfit text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-check text-sky-400"></i>
                    <span>Dokumen & Tagihan Proyek Terbaru</span>
                </h3>
                <p class="text-xs text-slate-400">RAB, DED, Invoice, Kwitansi, SP, PKS, PPJB, BAST</p>
            </div>
            <a href="{{ route('documents.index') }}" class="text-xs font-semibold text-sky-400 hover:text-sky-300 flex items-center gap-1">
                <span>Lihat Hub Dokumen</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentDocuments as $doc)
                <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700/80 hover:border-slate-600 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-700 text-sky-300">
                                {{ strtoupper($doc->type) }}
                            </span>

                            @if($doc->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Disetujui</span>
                            @elseif($doc->status === 'paid')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/30">Lunas</span>
                            @elseif($doc->status === 'pending_payment')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">Menunggu Bayar</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-700 text-slate-400">Draf</span>
                            @endif
                        </div>

                        <h4 class="text-xs font-bold text-white line-clamp-1 mb-1">{{ $doc->title }}</h4>
                        <p class="text-[11px] text-slate-400">{{ $doc->project ? $doc->project->name : 'N/A' }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-700/50 text-[11px]">
                        <span class="text-slate-400">Pengunggah: <strong class="text-slate-300">{{ $doc->uploader->name ?? 'System' }}</strong></span>
                        <a href="{{ route('documents.download', $doc->id) }}" class="text-sky-400 hover:text-sky-300 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-download"></i> Unduh
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-6 text-slate-500 text-xs">
                    Belum ada dokumen yang diunggah.
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Kurva S Bar Chart
        const kurvaCtx = document.getElementById('kurvaSChart').getContext('2d');
        new Chart(kurvaCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartProjectLabels) !!},
                datasets: [
                    {
                        label: 'Target Rencana (%)',
                        data: {!! json_encode($chartPlannedData) !!},
                        backgroundColor: 'rgba(14, 165, 233, 0.5)',
                        borderColor: '#0ea5e9',
                        borderWidth: 1.5,
                        borderRadius: 6
                    },
                    {
                        label: 'Realisasi Aktual (%)',
                        data: {!! json_encode($chartActualData) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: '#10b981',
                        borderWidth: 1.5,
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#94a3b8', callback: value => value + '%' },
                        grid: { color: '#334155' }
                    },
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#f8fafc', font: { family: 'Inter', size: 11 } } }
                }
            }
        });

        // 2. Cashflow Expense Doughnut Chart
        const cashCtx = document.getElementById('cashflowCategoryChart').getContext('2d');
        new Chart(cashCtx, {
            type: 'doughnut',
            data: {
                labels: ['Material', 'Labor / Upah', 'Operasional', 'Lain-lain'],
                datasets: [{
                    data: [
                        {{ $expensesByCategory['material'] }},
                        {{ $expensesByCategory['labor'] }},
                        {{ $expensesByCategory['operational'] }},
                        {{ $expensesByCategory['others'] }}
                    ],
                    backgroundColor: ['#0ea5e9', '#10b981', '#f59e0b', '#6366f1'],
                    borderWidth: 2,
                    borderColor: '#0f172a'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
@endsection
