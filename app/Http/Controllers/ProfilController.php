<?php
namespace App\Http\Controllers;
use App\Http\Requests\PerbaruiProfilRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
class ProfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profil.edit', [
            'user' => $request->user(),
        ]);
    }
    public function update(PerbaruiProfilRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
