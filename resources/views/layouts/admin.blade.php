<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistem POS</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* ... (Salin semua style CSS Anda dari file admin.blade.php sebelumnya) ... */
        
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body, html { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            height: 100%;
            line-height: 1.6;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background: #1f2937; /* Solid dark blue-gray */
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.025em;
        }

        .sidebar-logo-subtitle {
            font-size: 0.6875rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: -2px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1rem 0;
        }

        .sidebar-nav-section {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.6875rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .sidebar-nav li:not(.sidebar-nav-section) {
            margin: 0.25rem 0.75rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.85rem; /* Menambah jarak untuk ikon */
            color: #d1d5db; /* Light gray text */
            padding: 0.75rem 0.875rem;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            border-radius: 6px; /* Slightly smaller border radius */
            position: relative;
            font-weight: 500;
        }
        
        /* Ikon Sidebar */
        .sidebar-nav a .nav-icon {
            width: 18px; /* Memberi lebar tetap agar rapi */
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .sidebar-nav a:hover {
            color: #fff;
            background: #374151; /* Darker hover */
        }
        
        .sidebar-nav a:hover .nav-icon {
            opacity: 1;
        }

        .sidebar-nav a.active {
            color: #fff;
            background: #2563eb; /* Solid blue */
        }
        
        .sidebar-nav a.active .nav-icon {
            opacity: 1;
        }

        .sidebar-nav a.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 1rem 1.5rem;
        }

        /* Menu Badge */
        .menu-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            font-size: 0.625rem;
            font-weight: 700;
            border-radius: 10px;
            margin-left: auto;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-new {
            background: #10b981; /* Solid green */
            color: #fff;
            animation: none; /* No pulse */
        }

        .badge-soon {
            background: #f3f4f6; /* Light gray */
            color: #6b7280;
            border: none;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05); /* Subtle shadow */
        }

        .topbar-left h1 {
            margin: 0;
            font-size: 1.5rem; /* Sedikit lebih kecil */
            color: #1a1a1a;
            font-weight: 600; /* Sedikit lebih ringan */
            letter-spacing: -0.025em;
        }

        .topbar-breadcrumb {
            font-size: 0.8125rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .topbar-breadcrumb a {
            color: #2563eb;
            transition: color 0.2s;
        }

        .topbar-breadcrumb a:hover {
            color: #1e40af;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .topbar-info {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* Gap lebih kecil */
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .topbar-info .topbar-icon {
            font-size: 1rem;
            color: #6b7280;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f9fafb;
            border-radius: 8px;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }

        .topbar-user:hover {
            background: #f3f4f6;
        }

        .topbar-avatar {
            width: 32px;
            height: 32px;
            background: #2563eb; /* Solid blue */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .topbar-avatar .topbar-icon {
            font-size: 1.2rem;
            color: #fff;
        }

        .topbar-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .topbar-username {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 0.875rem;
        }

        .topbar-role {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Container */
        .container {
            padding: 2rem;
            /* max-width: 1400px; */ /* Dihapus agar full-width */
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            border-left: 4px solid;
            border-radius: 8px;
            animation: slideIn 0.3s ease-out;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: none; /* No shadow */
        }
        
        .alert-icon {
            font-size: 1.25rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #22c55e;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #ef4444;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: #2563eb; /* Solid blue */
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.4);
            z-index: 999;
            transition: transform 0.2s;
        }

        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 260px; /* Lebar kembali normal di mobile */
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .topbar {
                padding: 1rem 1.5rem;
            }

            .topbar-left h1 {
                font-size: 1.25rem;
            }

            .topbar-info {
                display: none;
            }

            .container {
                padding: 1.5rem;
            }
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-text">
                    <div class="sidebar-logo-title">POS System</div>
                    <div class="sidebar-logo-subtitle">Admin Panel</div>
                </div>
            </div>
        </div>
        
        <ul class="sidebar-nav">
            <li class="sidebar-nav-section">Menu Utama</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kartustok.index') }}" class="{{ request()->routeIs('admin.kartustok.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list nav-icon"></i>
                    <span>Kartu Stok</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-section">Master Data</li>
            <li>
                <a href="{{ route('admin.barang.index') }}" class="{{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                    <i class="fas fa-box nav-icon"></i>
                    <span>Data Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.satuan.index') }}" class="{{ request()->routeIs('admin.satuan.*') ? 'active' : '' }}">
                    <i class="fas fa-ruler-horizontal nav-icon"></i>
                    <span>Data Satuan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.vendor.index') }}" class="{{ request()->routeIs('admin.vendor.*') ? 'active' : '' }}">
                    <i class="fas fa-building nav-icon"></i>
                    <span>Data Vendor</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <span>Data User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.margin_penjualan.index') }}" class="{{ request()->routeIs('admin.margin_penjualan.*') ? 'active' : '' }}">
                    <i class="fas fa-percent nav-icon"></i>
                    <span>Margin Penjualan</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-section">Transaksi</li>
            <li>
                <a href="{{ route('admin.pengadaan.index') }}" class="{{ request()->routeIs('admin.pengadaan.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart nav-icon"></i>
                    <span>Pengadaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.penerimaan.index') }}" class="{{ request()->routeIs('admin.penerimaan.*') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading nav-icon"></i>
                    <span>Penerimaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.penjualan.index') }}" class="{{ request()->routeIs('admin.penjualan.*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register nav-icon"></i>
                    <span>Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.retur_penjualan.index') }}" class="{{ request()->routeIs('admin.retur_penjualan.*') ? 'active' : '' }}">
                    <i class="fas fa-undo nav-icon"></i>
                    <span>Retur</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-section">System</li>
            <li>
                <a href="{{ route('login') }}"> <i class="fas fa-home nav-icon"></i>
                    <span>Kembali ke Home</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); if(confirm('Yakin ingin logout?')) { document.getElementById('logout-form').submit(); }">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <h1>@yield('page-title', 'Halaman Admin')</h1>
                <div class="topbar-breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        / @yield('page-title', 'Dashboard')
                    @endif
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-info">
                    <i class="fas fa-calendar-alt topbar-icon"></i>
                    <span id="currentTime">{{ date('d M Y, H:i') }}</span>
                </div> 
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        <i class="fas fa-user-circle topbar-icon"></i>
                    </div>
                    <div class="topbar-user-info">
                        <div class="topbar-username">{{ session('user') ? session('user')->username : 'Admin' }}</div>
                        <div class="topbar-role">Administrator</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle alert-icon"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
        <i class="fas fa-bars"></i>
    </button>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // ... (Salin semua script JS Anda dari file admin.blade.php sebelumnya) ...
        
        // Update waktu real-time
        function updateTime() {
            const now = new Date();
            const options = { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const timeString = now.toLocaleDateString('id-ID', options);
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }

        // Update setiap menit
        setInterval(updateTime, 60000);
        updateTime();

        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // Mobile menu toggle
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });

                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                            sidebar.classList.remove('active');
                        }
                    }
                });
            }
        });

        // Show loading on form submit
        document.addEventListener('submit', function(e) {
            if (e.target.tagName === 'FORM') {
                const loadingOverlay = document.getElementById('loadingOverlay');
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    @stack('scripts')
</body>
</html>