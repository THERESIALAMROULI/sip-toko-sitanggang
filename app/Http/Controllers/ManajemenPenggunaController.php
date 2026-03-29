<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ManajemenPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:owner,admin,kasir'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ]);

        $usersQuery = Pengguna::query();

        if (! empty($validated['q'])) {
            $search = trim($validated['q']);

            $usersQuery->where(function ($query) use ($search, $hasUsernameColumn) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');

                if ($hasUsernameColumn) {
                    $query->orWhere('username', 'like', '%'.$search.'%');
                }
            });
        }

        if (! empty($validated['role'])) {
            $usersQuery->where('role', $validated['role']);
        }

        if (! empty($validated['status']) && $hasStatusColumn) {
            $usersQuery->where('status', $validated['status']);
        }

        $users = $usersQuery
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $filters = [
            'q' => $validated['q'] ?? null,
            'role' => $validated['role'] ?? null,
            'status' => $validated['status'] ?? null,
        ];

        return view('pengguna.index', compact('users', 'filters', 'hasStatusColumn', 'hasUsernameColumn'));
    }

    public function create()
    {
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        return view('pengguna.create', compact('hasStatusColumn', 'hasUsernameColumn'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:owner,admin,kasir'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = ['nullable', 'string', 'max:50', 'unique:users,username'];
        }

        if (Schema::hasColumn('users', 'status')) {
            $rules['status'] = ['required', 'in:aktif,nonaktif'];
        }

        $validated = $request->validate($rules);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'],
        ];

        if (Schema::hasColumn('users', 'nama')) {
            $payload['nama'] = $validated['name'];
        }

        if (Schema::hasColumn('users', 'username')) {
            $payload['username'] = $validated['username'] ?? (strstr($validated['email'], '@', true) ?: $validated['email']);
        }

        if (Schema::hasColumn('users', 'status')) {
            $payload['status'] = $validated['status'];
        }

        Pengguna::create($payload);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(Pengguna $user)
    {
        $hasStatusColumn = Schema::hasColumn('users', 'status');
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        return view('pengguna.edit', compact('user', 'hasStatusColumn', 'hasUsernameColumn'));
    }

    public function update(Request $request, Pengguna $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:owner,admin,kasir'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = ['nullable', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)];
        }

        if (Schema::hasColumn('users', 'status')) {
            $rules['status'] = ['required', 'in:aktif,nonaktif'];
        }

        $validated = $request->validate($rules);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        if (Schema::hasColumn('users', 'nama')) {
            $payload['nama'] = $validated['name'];
        }

        if (Schema::hasColumn('users', 'username')) {
            $payload['username'] = $validated['username'] ?? (strstr($validated['email'], '@', true) ?: $validated['email']);
        }

        if (Schema::hasColumn('users', 'status')) {
            $payload['status'] = $validated['status'];
        }

        $user->update($payload);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function toggleStatus(Pengguna $user)
    {
        if (! Schema::hasColumn('users', 'status')) {
            return redirect()->route('users.index')
                ->with('error', 'Kolom status user tidak tersedia di database ini.');
        }

        if ((int) $user->id === (int) auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Status akun yang sedang dipakai tidak bisa dinonaktifkan.');
        }

        $newStatus = ($user->status ?? 'aktif') === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return redirect()->route('users.index')
            ->with('success', 'Status user berhasil diperbarui.');
    }

    public function destroy(Pengguna $user)
    {
        if ((int) $user->id === (int) auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
