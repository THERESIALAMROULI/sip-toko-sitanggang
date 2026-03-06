<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIPA Sitanggang') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-brand">
            <div class="auth-brand-icon">S</div>
            <h1>SIPA Sitanggang</h1>
            <p>Sistem Informasi Penjualan Toko</p>
        </div>

        {{ $slot }}
    </div>
</div>
</body>
</html>
