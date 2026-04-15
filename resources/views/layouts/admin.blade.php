<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SIPA Sitanggang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $user = Auth::user();
    $role = $user->role ?? 'kasir';
    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp
<div class="layout">
    <aside id="sidebar">
        <div class="sb-brand">
            <div class="sb-logo">
                <div class="sb-logo-icon">S</div>
                <div>
                    <div class="sb-logo-text">SIPA Sitanggang</div>
                    <div class="sb-logo-sub">Penjualan dan stok</div>
                </div>
            </div>
            <div class="sb-user">
                <div class="sb-avatar {{ $role }}">{{ $initial }}</div>
                <div class="sb-user-info">
                    <div class="name">{{ $user->name }}</div>
                    <div class="role">{{ ucfirst($role) }}</div>
                </div>
            </div>
        </div>
        <nav class="sb-nav">
            <div class="sb-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sb-item-icon">D</span>
                <span>Dashboard</span>
            </a>
            @if ($role === 'admin')
                <a href="{{ route('users.index') }}" class="sb-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">U</span>
                    <span>Pengguna</span>
                </a>
                <a href="{{ route('kategoris.index') }}" class="sb-item {{ request()->routeIs('kategoris.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">K</span>
                    <span>Kategori Produk</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="sb-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">S</span>
                    <span>Pemasok</span>
                </a>
                <a href="{{ route('products.index') }}" class="sb-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">P</span>
                    <span>Produk</span>
                </a>
                <a href="{{ route('customers.index') }}" class="sb-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">C</span>
                    <span>Pelanggan</span>
                </a>
                <a href="{{ route('expense_categories.index') }}" class="sb-item {{ request()->routeIs('expense_categories.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">B</span>
                    <span>Kategori Biaya</span>
                </a>
                <a href="{{ route('expenses.index') }}" class="sb-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">O</span>
                    <span>Pengeluaran</span>
                </a>
            @endif
            @if ($role === 'kasir')
                <a href="{{ route('transactions.index') }}" class="sb-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">T</span>
                    <span>Transaksi</span>
                </a>
                <a href="{{ route('receivables.index') }}" class="sb-item {{ request()->routeIs('receivables.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">R</span>
                    <span>Piutang</span>
                </a>
                <a href="{{ route('stocks.check') }}" class="sb-item {{ request()->routeIs('stocks.check') ? 'active' : '' }}">
                    <span class="sb-item-icon">C</span>
                    <span>Cek Stok</span>
                </a>
            @endif
            @if ($role === 'admin')
                <a href="{{ route('stok_histories.index') }}" class="sb-item {{ request()->routeIs('stok_histories.*') ? 'active' : '' }}">
                    <span class="sb-item-icon">H</span>
                    <span>Manajemen Stok</span>
                </a>
            @endif
            @if (in_array($role, ['admin', 'owner'], true))
                <a href="{{ route('reports.stock') }}" class="sb-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                    <span class="sb-item-icon">S</span>
                    <span>Laporan Stok</span>
                </a>
            @endif
            @if ($role === 'owner')
                <a href="{{ route('reports.sales') }}" class="sb-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                    <span class="sb-item-icon">L</span>
                    <span>Laporan Penjualan</span>
                </a>
                <a href="{{ route('reports.receivables') }}" class="sb-item {{ request()->routeIs('reports.receivables') ? 'active' : '' }}">
                    <span class="sb-item-icon">U</span>
                    <span>Laporan Piutang</span>
                </a>
                <a href="{{ route('reports.expenses') }}" class="sb-item {{ request()->routeIs('reports.expenses') ? 'active' : '' }}">
                    <span class="sb-item-icon">B</span>
                    <span>Laporan Pengeluaran</span>
                </a>
            @endif
            <div class="sb-section-title">Akun</div>
            <a href="{{ route('profile.edit') }}" class="sb-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="sb-item-icon">A</span>
                <span>Profil</span>
            </a>
        </nav>
        <div class="sb-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sb-logout">
                    <span class="sb-item-icon">X</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="page-content">
        <header class="topbar">
            <div class="topbar-left">
                <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Buka menu">=</button>
                <div>
                    <div class="page-title">@yield('title', 'Dashboard')</div>
                    <div class="page-sub">@yield('subtitle', 'Kelola data toko')</div>
                </div>
            </div>
            <div class="topbar-right">
                <span class="badge badge-gray">{{ now()->format('d M Y') }}</span>
                <span class="badge badge-blue">{{ ucfirst($role) }}</span>
            </div>
        </header>
        <main class="content-area">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="stack-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>
<script>
    (() => {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar || !toggle || !backdrop) {
            return;
        }
        const closeSidebar = () => {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        };
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('show');
        });
        backdrop.addEventListener('click', closeSidebar);
        window.addEventListener('resize', () => {
            if (window.innerWidth > 880) {
                closeSidebar();
            }
        });
    })();
</script>
@stack('scripts')
</body>
</html>
