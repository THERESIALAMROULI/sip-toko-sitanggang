<x-guest-layout>
    <h2 class="auth-title">Masuk ke Sistem</h2>
    <p class="auth-muted">Gunakan username dan password akun Anda.</p>

    @if (session('status'))
        <div class="auth-status success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-status error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" placeholder="Masukkan username">
            @error('username')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-actions">
            <label for="remember_me" class="auth-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="auth-btn">Masuk ke Sistem</button>
    </form>

    @if (Route::has('register'))
        <p class="auth-note">
            Belum punya akun?
            <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a>
        </p>
    @endif
</x-guest-layout>
