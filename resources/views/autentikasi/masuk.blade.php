{{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
<x-guest-layout>
    {{-- Judul halaman login yang menjelaskan fungsi form kepada pengguna. --}}
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <h2 class="auth-title">Masuk ke Sistem</h2>
    {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
    <p class="auth-muted">Gunakan username dan password akun Anda.</p>

    {{-- Menampilkan pesan status dari sistem, misalnya setelah logout berhasil. --}}
    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
    @if (session('status'))
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-status success">{{ session('status') }}</div>
    {{-- Menutup percabangan kondisi pada template Blade. --}}
    @endif

    {{-- Menampilkan semua error validasi agar pengguna tahu input mana yang bermasalah. --}}
    {{-- Memeriksa kondisi untuk menentukan elemen mana yang perlu ditampilkan. --}}
    @if ($errors->any())
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-status error">
            {{-- Melakukan perulangan untuk menampilkan data secara berulang. --}}
            @foreach ($errors->all() as $error)
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div>{{ $error }}</div>
            {{-- Menutup perulangan Blade yang sedang dijalankan. --}}
            @endforeach
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>
    {{-- Menutup percabangan kondisi pada template Blade. --}}
    @endif

    {{-- Form login dikirim ke route login dengan metode POST. --}}
    {{-- Membuka form untuk mengirim data dari pengguna ke server. --}}
    <form method="POST" action="{{ route('login') }}">
        {{-- Menyisipkan token CSRF untuk melindungi form dari serangan lintas situs. --}}
        @csrf

        {{-- Input username digunakan sebagai identitas akun saat proses autentikasi. --}}
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-field">
            {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
            <label for="username">Username</label>
            {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Masukkan username">
            {{-- Menampilkan pesan error validasi untuk field terkait. --}}
            @error('username')
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field-error">{{ $message }}</div>
            {{-- Menutup blok tampilan error validasi. --}}
            @enderror
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Input password dipakai untuk memverifikasi kecocokan akun pengguna. --}}
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-field">
            {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
            <label for="password">Password</label>
            {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            {{-- Menampilkan pesan error validasi untuk field terkait. --}}
            @error('password')
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <div class="field-error">{{ $message }}</div>
            {{-- Menutup blok tampilan error validasi. --}}
            @enderror
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Opsi ini memungkinkan sesi login diingat oleh browser. --}}
        {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
        <div class="auth-actions">
            {{-- Menampilkan label agar pengguna memahami fungsi input yang terkait. --}}
            <label for="remember_me" class="auth-checkbox">
                {{-- Mendefinisikan field input yang akan diisi oleh pengguna. --}}
                <input id="remember_me" type="checkbox" name="remember">
                {{-- Membentuk elemen HTML sebagai bagian dari antarmuka halaman. --}}
                <span>Ingat saya</span>
            {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
            </label>
        {{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
        </div>

        {{-- Tombol untuk mengirim data login ke server. --}}
        {{-- Mendefinisikan tombol aksi yang bisa digunakan pengguna. --}}
        <button type="submit" class="auth-btn">Masuk ke Sistem</button>
    {{-- Menutup form setelah seluruh input selesai didefinisikan. --}}
    </form>
{{-- Menutup elemen HTML yang dibuka sebelumnya. --}}
</x-guest-layout>
