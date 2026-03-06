<x-guest-layout>
    <h2 class="auth-title">Registrasi Akun</h2>
    <p class="auth-muted">Buat akun baru untuk mengakses sistem.</p>

    @if ($errors->any())
        <div class="auth-status error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap">
            @error('name')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan password">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
            @error('password_confirmation')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-actions">
            <a class="auth-link" href="{{ route('login') }}">
                Sudah punya akun?
            </a>
        </div>

        <button type="submit" class="auth-btn">Daftar Akun</button>
    </form>
</x-guest-layout>
