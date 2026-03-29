<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Autentikasi\PermintaanMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SesiMasukController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('autentikasi.masuk');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(PermintaanMasuk $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
