{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<!DOCTYPE html>
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<html lang="id">
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<head>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta charset="utf-8">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <title>{{ config('app.name', 'SIPA Sitanggang') }}</title>

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
<body class="auth-page">
{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<div class="auth-shell">
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <div class="auth-card">
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-brand">
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <div class="auth-brand-icon">S</div>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <h1>SIPA Sitanggang</h1>
            {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
            <p>Sistem Informasi Penjualan Toko</p>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Menampilkan data dinamis dari server ke halaman. --}}
        {{ $slot }}
    {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
    </div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</div>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</body>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</html>
