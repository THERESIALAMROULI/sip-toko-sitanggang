<x-guest-layout>
    <h2 class="auth-title">Konfirmasi Password</h2>
    <p class="auth-muted">Masukkan password Anda untuk melanjutkan ke area aman.</p>

    @if ($errors->any())
        <div class="auth-status error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="auth-field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-btn">Konfirmasi</button>
    </form>
</x-guest-layout>
