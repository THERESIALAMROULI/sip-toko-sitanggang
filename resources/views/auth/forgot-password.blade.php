<x-guest-layout>
    <h2 class="auth-title">Lupa Password</h2>
    <p class="auth-muted">
        Masukkan email akun Anda. Kami akan mengirimkan tautan untuk reset password.
    </p>

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

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Kirim Link Reset</button>
    </form>

    <p class="auth-note">
        <a href="{{ route('login') }}" class="auth-link">Kembali ke halaman login</a>
    </p>
</x-guest-layout>
