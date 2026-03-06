<x-guest-layout>
    <h2 class="auth-title">Reset Password</h2>
    <p class="auth-muted">Masukkan password baru untuk akun Anda.</p>

    @if ($errors->any())
        <div class="auth-status error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password Baru</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Simpan Password Baru</button>
    </form>
</x-guest-layout>
