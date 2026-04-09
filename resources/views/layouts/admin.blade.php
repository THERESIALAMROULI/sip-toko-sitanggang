{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<!DOCTYPE html>
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<html lang="id">
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<head>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta charset="UTF-8">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <title>@yield('title', 'Dashboard') - SIPA Sitanggang</title>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Memuat aset CSS dan JavaScript yang dibutuhkan halaman ini. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</head>
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<body>
{{-- Membuka blok PHP pada template Blade. --}}
@php
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $user = Auth::user();
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $role = $user->role ?? 'kasir';
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
{{-- Menutup blok PHP pada template Blade. --}}
@endphp

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="layout">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <aside id="sidebar">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sb-brand">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sb-logo">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sb-logo-icon">S</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="sb-logo-text">SIPA Sitanggang</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="sb-logo-sub">Sistem Penjualan</div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sb-user">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sb-avatar {{ $role }}">{{ $initial }}</div>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="sb-user-info">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="name">{{ $user->name }}</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="role">{{ strtoupper($role) }}</div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <nav class="sb-nav">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sb-section-title">Menu Utama</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span class="sb-item-icon">D</span>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span>Dashboard</span>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </a>

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($role === 'admin')
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('users.index') }}" class="sb-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">U</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Manajemen User</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('kategoris.index') }}" class="sb-item {{ request()->routeIs('kategoris.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">K</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Kategori</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('suppliers.index') }}" class="sb-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">S</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Supplier</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('products.index') }}" class="sb-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">P</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Produk</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('customers.index') }}" class="sb-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">C</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Pelanggan</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('expense_categories.index') }}" class="sb-item {{ request()->routeIs('expense_categories.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">B</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Kategori Biaya</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('expenses.index') }}" class="sb-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">O</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Biaya Operasional</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($role === 'kasir')
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('transactions.index') }}" class="sb-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">T</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Transaksi</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('receivables.index') }}" class="sb-item {{ request()->routeIs('receivables.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">R</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Piutang</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('stocks.check') }}" class="sb-item {{ request()->routeIs('stocks.check') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">C</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Cek Stok</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($role === 'admin')
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('stok_histories.index') }}" class="sb-item {{ request()->routeIs('stok_histories.*') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">H</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Manajemen Stok</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if (in_array($role, ['admin', 'owner'], true))
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('reports.sales') }}" class="sb-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">L</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Laporan Penjualan</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('reports.receivables') }}" class="sb-item {{ request()->routeIs('reports.receivables') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">U</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Laporan Utang</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('reports.stock') }}" class="sb-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">S</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Laporan Stok</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <a href="{{ route('reports.expenses') }}" class="sb-item {{ request()->routeIs('reports.expenses') ? 'active' : '' }}">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">B</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Laporan Pengeluaran</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </a>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="sb-section-title">Akun</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <a href="{{ route('profile.edit') }}" class="sb-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span class="sb-item-icon">A</span>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span>Profil</span>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </a>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </nav>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="sb-footer">
            {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
            <form method="POST" action="{{ route('logout') }}">
                {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
                @csrf
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button type="submit" class="sb-logout">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span class="sb-item-icon">X</span>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <span>Logout</span>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </button>
            {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
            </form>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </aside>

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="page-content">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <header class="topbar">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="topbar-left">
                {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
                <button id="sidebarToggle" class="sidebar-toggle" type="button" aria-label="Toggle menu">=</button>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="page-title">@yield('title', 'Dashboard')</div>
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="page-sub">@yield('subtitle', 'Sistem Informasi Penjualan Toko Sitanggang')</div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="topbar-right">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span class="badge badge-gray">{{ now()->format('d M Y') }}</span>
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span class="badge badge-blue">{{ strtoupper($role) }}</span>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </div>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </header>

        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <main class="content-area">
            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if (session('success'))
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="alert alert-success">{{ session('success') }}</div>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if (session('error'))
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="alert alert-danger">{{ session('error') }}</div>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
            @if ($errors->any())
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="alert alert-danger">
                    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                    <div class="stack-sm">
                        {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
                        @foreach ($errors->all() as $error)
                            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                            <div>{{ $error }}</div>
                        {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
                        @endforeach
                    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                    </div>
                {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
                </div>
            {{-- Menutup percabangan kondisi pada template Blade. --}}
            @endif

            {{-- Menjalankan directive Blade sebagai bagian dari logika tampilan. --}}
            @yield('content')
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </main>
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>

{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>

{{-- Membuka blok JavaScript untuk interaksi tambahan pada halaman. --}}
<script>
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    (() => {
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const sidebar = document.getElementById('sidebar');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const toggle = document.getElementById('sidebarToggle');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const backdrop = document.getElementById('sidebarBackdrop');

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        if (!sidebar || !toggle || !backdrop) {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            return;
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        }

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        const closeSidebar = () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            sidebar.classList.remove('open');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            backdrop.classList.remove('show');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        };

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        toggle.addEventListener('click', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            sidebar.classList.toggle('open');
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            backdrop.classList.toggle('show');
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        backdrop.addEventListener('click', closeSidebar);

        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        window.addEventListener('resize', () => {
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            if (window.innerWidth > 880) {
                {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
                closeSidebar();
            {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
            }
        {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
        });
    {{-- Baris ini merupakan bagian dari tampilan halaman pada sistem. --}}
    })();
{{-- Menutup blok JavaScript pada halaman ini. --}}
</script>

{{-- Menjalankan directive Blade sebagai bagian dari logika tampilan. --}}
@stack('scripts')
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</body>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</html>
