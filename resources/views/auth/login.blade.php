<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0ea5e9">
    <title>Login - RBK TRACKER</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-outfit { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">

    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-900/90 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl backdrop-blur-xl">
        
        <!-- Left Side: Login Form -->
        <div class="flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white shadow-xl shadow-sky-500/30">
                        <i class="fa-solid fa-building-user text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold font-outfit text-white tracking-wider">RBK TRACKER</h1>
                        <p class="text-xs text-sky-400 font-medium">Project Monitoring & Document Hub PWA</p>
                    </div>
                </div>

                <h2 class="text-xl font-bold font-outfit text-slate-100 mb-1">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-400 mb-6">Silakan gunakan email & password terdaftar atau pilih role demo cepat di sebelah kanan.</p>

                @if($errors->any())
                    <div class="p-3 mb-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-200 text-xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Email Pengguna</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-xs">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email', 'bod@rbktracker.com') }}" required 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi (Password)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-xs">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" value="password" required 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/90 border border-slate-700 text-sm text-white focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-sky-500 focus:ring-0">
                            <span>Ingat Saya</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white font-semibold text-sm shadow-lg shadow-sky-500/25 transition-all">
                        Masuk Sistem
                    </button>
                </form>
            </div>

            <p class="text-[11px] text-slate-500 text-center">
                RBK TRACKER &copy; 2026. Powered by Laravel 11 & PostgreSQL PWA.
            </p>
        </div>

        <!-- Right Side: 1-Click Demo Role Switcher -->
        <div class="bg-slate-950/60 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2 text-sky-400 font-semibold text-xs uppercase tracking-wider mb-2">
                    <i class="fa-solid fa-rocket"></i>
                    <span>Quick Demo Access (3-Tier RBAC)</span>
                </div>
                <h3 class="text-base font-bold font-outfit text-white mb-4">Pilih Peran Demo untuk Uji Coba</h3>

                <div class="space-y-3">
                    <!-- Demo Card 1: Full Access -->
                    <a href="{{ route('login.demo', 'full_access') }}" 
                       class="group block p-3.5 rounded-xl bg-slate-900 border border-emerald-500/30 hover:border-emerald-500/80 transition-all hover:shadow-lg hover:shadow-emerald-500/10">
                        <div class="flex items-center justify-between mb-1">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-400">
                                <i class="fa-solid fa-shield-halved"></i> 1. Full Access
                            </span>
                            <span class="text-[10px] text-slate-500 group-hover:text-emerald-400 transition-colors">
                                Klik untuk Masuk <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-200">BoD / General Manager / Finance</p>
                        <p class="text-[11px] text-slate-400 mt-1">Full CRUD proyek, input transaksi cashflow, & persetujuan dokumen.</p>
                    </a>

                    <!-- Demo Card 2: Partial Access -->
                    <a href="{{ route('login.demo', 'partial_access') }}" 
                       class="group block p-3.5 rounded-xl bg-slate-900 border border-amber-500/30 hover:border-amber-500/80 transition-all hover:shadow-lg hover:shadow-amber-500/10">
                        <div class="flex items-center justify-between mb-1">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-400">
                                <i class="fa-solid fa-user-pen"></i> 2. Partial Access
                            </span>
                            <span class="text-[10px] text-slate-500 group-hover:text-amber-400 transition-colors">
                                Klik untuk Masuk <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-200">Site Team & HO (Admin, Arsitek, Pengawas)</p>
                        <p class="text-[11px] text-slate-400 mt-1">Update progres Kurva S, kelola unit kavling, & unggah draf dokumen.</p>
                    </a>

                    <!-- Demo Card 3: Restricted Access -->
                    <a href="{{ route('login.demo', 'restricted_access') }}" 
                       class="group block p-3.5 rounded-xl bg-slate-900 border border-rose-500/30 hover:border-rose-500/80 transition-all hover:shadow-lg hover:shadow-rose-500/10">
                        <div class="flex items-center justify-between mb-1">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-400">
                                <i class="fa-solid fa-eye"></i> 3. Restricted Access (View Only)
                            </span>
                            <span class="text-[10px] text-slate-500 group-hover:text-rose-400 transition-colors">
                                Klik untuk Masuk <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                        <p class="text-xs font-medium text-slate-200">Client Investor & Visitor</p>
                        <p class="text-[11px] text-slate-400 mt-1">HANYA dapat melihat metrik dashboard, Kurva S & dokumen yang telah disetujui.</p>
                    </a>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
