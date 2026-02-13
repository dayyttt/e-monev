<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Monev System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
        <div class="h-full px-3 py-4 overflow-y-auto gradient-bg">
            <!-- Logo -->
            <div class="flex items-center justify-center mb-8 mt-2">
                <div class="text-center">
                    <div class="bg-white rounded-full p-3 inline-block mb-2">
                        <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">E-Monev</h2>
                    <p class="text-purple-200 text-sm">Monitoring & Evaluasi</p>
                </div>
            </div>

            <!-- Navigation -->
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                
                <li class="pt-4 pb-2">
                    <span class="text-purple-200 text-xs font-semibold uppercase px-3">Master Data</span>
                </li>
                
                <li>
                    <a href="{{ route('bidang.index') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('bidang.*') ? 'active' : '' }}">
                        <i class="fas fa-building w-5"></i>
                        <span class="ml-3">Bidang</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('kegiatan.index') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks w-5"></i>
                        <span class="ml-3">Kegiatan</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('realisasi.input') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('realisasi.input') ? 'active' : '' }}">
                        <i class="fas fa-keyboard w-5"></i>
                        <span class="ml-3">Input Realisasi</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('monitoring.index') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="ml-3">Monitoring</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('anggaran.index') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('anggaran.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave w-5"></i>
                        <span class="ml-3">Anggaran</span>
                    </a>
                </li>
                
                <li class="pt-4 pb-2">
                    <span class="text-purple-200 text-xs font-semibold uppercase px-3">Laporan</span>
                </li>
                
                <li>
                    <a href="{{ route('realisasi.laporan.bulanan') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('realisasi.laporan.bulanan') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="ml-3">Laporan Bulanan</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('realisasi.laporan.triwulanan') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('realisasi.laporan.triwulanan') ? 'active' : '' }}">
                        <i class="fas fa-calendar-week w-5"></i>
                        <span class="ml-3">Laporan Triwulanan</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('realisasi.laporan.tahunan') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('realisasi.laporan.tahunan') ? 'active' : '' }}">
                        <i class="fas fa-calendar w-5"></i>
                        <span class="ml-3">Laporan Tahunan</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('laporan.rekap-bidang') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('laporan.rekap-bidang') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-3">Rekap per Bidang</span>
                    </a>
                </li>
                
                <li class="pt-4 pb-2">
                    <span class="text-purple-200 text-xs font-semibold uppercase px-3">Sistem</span>
                </li>
                
                <li>
                    <a href="{{ route('pengaturan') }}" class="sidebar-link flex items-center p-3 text-white rounded-lg hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('pengaturan') ? 'active' : '' }}">
                        <i class="fas fa-cog w-5"></i>
                        <span class="ml-3">Pengaturan</span>
                    </a>
                </li>
            </ul>

            <!-- User Info -->
            <div class="absolute bottom-4 left-3 right-3">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="bg-white rounded-full p-2">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-white font-semibold text-sm">Admin User</p>
                            <p class="text-purple-200 text-xs">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="sm:ml-64">
        <!-- Top Navbar -->
        <nav class="bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-30">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button id="toggleSidebar" class="sm:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800 ml-4">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <button class="text-gray-600 hover:text-gray-900 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </button>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ date('d M Y') }}</span>
                        <i class="fas fa-calendar text-gray-400"></i>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script>
        // Toggle sidebar on mobile
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
