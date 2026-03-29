<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromptVerifikasiSurelController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('autentikasi.verifikasi-surel');
    }
}
