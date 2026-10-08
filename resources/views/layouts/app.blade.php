<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="description" content="RBK TRACKER - Platform Monitoring Proyek, Keuangan & Dokumen Terpusat">
    <title>@yield('title', 'Dashboard') - RBK TRACKER</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js & Chart.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-outfit { font-family: 'Outfit', sans-serif; }
        
        /* Custom Scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full flex flex-col antialiased bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false }">

    <!-- Main Application Wrapper -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900/95 backdrop-blur-md border-r border-slate-800 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col justify-between"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div>
                <!-- Brand Header -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/25 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-building-user text-xl"></i>
                        </div>
                        <div>
                            <span class="font-outfit text-xl font-bold tracking-wider text-white">RBK TRACKER</span>
                            <span class="block text-xs font-medium text-sky-400">PWA Monitoring System</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="px-4 py-6 space-y-1.5">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white shadow-md shadow-sky-600/30 font-semibold' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-200' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                        <span>Dashboard Overview</span>
                    </a>

                    <a href="{{ route('projects.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('projects.*') ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white shadow-md shadow-sky-600/30 font-semibold' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-200' }}">
                        <i class="fa-solid fa-city w-5 text-center text-base"></i>
                        <span>Proyek & Unit Kavling</span>
                    </a>

                    <a href="{{ route('cashflows.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('cashflows.*') ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white shadow-md shadow-sky-600/30 font-semibold' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-200' }}">
                        <i class="fa-solid fa-wallet w-5 text-center text-base"></i>
                        <span>Modul Keuangan (Cashflow)</span>
                    </a>

                    <a href="{{ route('documents.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('documents.*') ? 'bg-gradient-to-r from-sky-600 to-indigo-600 text-white shadow-md shadow-sky-600/30 font-semibold' : 'text-slate-400 hover:bg-slate-800/70 hover:text-slate-200' }}">
                        <i class="fa-solid fa-folder-closed w-5 text-center text-base"></i>
                        <span>Document Hub</span>
                    </a>
                </nav>
            </div>

            <!-- User Role Profile & Demo Role Switcher -->
            <div class="p-4 border-t border-slate-800 bg-slate-900/60" x-data="{ dropdownOpen: false }">
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" 
                            class="w-full flex items-center justify-between p-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 transition-colors border border-slate-700/60">
                        <div class="flex items-center gap-3 overflow-hidden text-left">
                            <div class="w-9 h-9 rounded-lg bg-slate-700 flex items-center justify-center text-slate-200 shrink-0 font-bold font-outfit">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->department_title ?? 'Staff' }}</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-up text-xs text-slate-400" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Role Switcher Dropdown -->
                    <div x-show="dropdownOpen" 
                         @click.outside="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute bottom-14 left-0 right-0 z-50 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl p-2 space-y-1 text-xs">
                        <div class="px-2 py-1 text-[10px] uppercase font-bold tracking-wider text-slate-400 border-b border-slate-700 mb-1">
                            Switch Demo Access Tier
                        </div>

                        <a href="{{ route('login.demo', 'full_access') }}" 
                           class="flex items-center justify-between p-2 rounded-lg hover:bg-emerald-950/60 hover:text-emerald-300 text-slate-300 transition-colors">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span class="font-medium">Full Access (BoD/Finance)</span>
                            </div>
                            @if(auth()->user()->isFullAccess())
                                <i class="fa-solid fa-check text-emerald-400"></i>
                            @endif
                        </a>

                        <a href="{{ route('login.demo', 'partial_access') }}" 
                           class="flex items-center justify-between p-2 rounded-lg hover:bg-amber-950/60 hover:text-amber-300 text-slate-300 transition-colors">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span class="font-medium">Partial Access (Site/HO)</span>
                            </div>
                            @if(auth()->user()->isPartialAccess())
                                <i class="fa-solid fa-check text-amber-400"></i>
                            @endif
                        </a>

                        <a href="{{ route('login.demo', 'restricted_access') }}" 
                           class="flex items-center justify-between p-2 rounded-lg hover:bg-rose-950/60 hover:text-rose-300 text-slate-300 transition-colors">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                                <span class="font-medium">Restricted Access (Client)</span>
                            </div>
                            @if(auth()->user()->isRestrictedAccess())
                                <i class="fa-solid fa-check text-rose-400"></i>
                            @endif
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-slate-700">
                            @csrf
                            <button type="submit" class="w-full text-left p-2 rounded-lg text-rose-400 hover:bg-rose-950/40 font-medium transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Keluar Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
            
            <!-- Top Navbar -->
            <header class="h-16 bg-slate-900/80 backdrop-blur-md border-b border-slate-800/80 px-4 md:px-8 flex items-center justify-between shrink-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden text-slate-300 hover:text-white p-2 rounded-lg bg-slate-800">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-base md:text-lg font-bold font-outfit text-white tracking-wide">
                            @yield('title', 'RBK Tracker')
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Current Role Badge -->
                    @if(auth()->user()->isFullAccess())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                            <i class="fa-solid fa-shield-halved text-[10px]"></i>
                            <span>Full Access</span>
                        </span>
                    @elseif(auth()->user()->isPartialAccess())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                            <i class="fa-solid fa-user-pen text-[10px]"></i>
                            <span>Partial Access</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                            <i class="fa-solid fa-eye text-[10px]"></i>
                            <span>Restricted (View Only)</span>
                        </span>
                    @endif

                    <!-- PWA Offline Status Indicator -->
                    <div id="pwa-status-badge" class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-sky-950/60 text-sky-400 border border-sky-800">
                        <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                        <span>PWA Ready</span>
                    </div>
                </div>
            </header>

            <!-- Main Body Scrollable View -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8 space-y-6">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-600/50 text-emerald-200 text-sm flex items-center gap-3 shadow-lg">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                        <span class="flex-1">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-200 text-sm flex items-center gap-3 shadow-lg">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400 text-lg"></i>
                        <span class="flex-1">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <!-- Service Worker PWA Registration Script -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('PWA ServiceWorker registered with scope: ', registration.scope);
                }, function(err) {
                    console.log('PWA ServiceWorker registration failed: ', err);
                });
            });
        }

        // Online / Offline Status
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        function updateOnlineStatus() {
            const badge = document.getElementById('pwa-status-badge');
            if (badge) {
                if (navigator.onLine) {
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><span>Online (PWA)</span>';
                    badge.className = 'hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-950/60 text-emerald-400 border border-emerald-800';
                } else {
                    badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span><span>Offline Mode</span>';
                    badge.className = 'hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-950/60 text-amber-400 border border-amber-800';
                }
            }
        }
        updateOnlineStatus();
    </script>

    @stack('scripts')
</body>
</html>
