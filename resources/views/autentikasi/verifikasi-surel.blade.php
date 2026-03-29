<x-guest-layout>
    <h2 class="auth-title">Verifikasi Email</h2>
    <p class="auth-muted">
        Sebelum mulai, verifikasi alamat email melalui link yang sudah kami kirimkan.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status success">
            Link verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="stack-md">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="auth-btn">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-secondary w-full">Logout</button>
        </form>

        <p class="auth-note">
            Sudah verifikasi?
            <a href="{{ route('login') }}" class="auth-link">Kembali ke login</a>
        </p>
    </div>
</x-guest-layout>
