@extends('layouts.admin')
@section('title', 'Data Pengguna')
@section('subtitle', 'Data pengguna')
@section('content')
<div class="stack-lg">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Filter Pengguna</div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="search-row">
                <input type="text" name="q" class="search-input" placeholder="Cari nama/username..." value="{{ $filters['q'] ?? '' }}">
                <select name="role" class="filter-sel">
                    <option value="">Semua Peran</option>
                    <option value="owner" @selected(($filters['role'] ?? null) === 'owner')>Owner</option>
                    <option value="admin" @selected(($filters['role'] ?? null) === 'admin')>Admin</option>
                    <option value="kasir" @selected(($filters['role'] ?? null) === 'kasir')>Kasir</option>
                </select>
                @if ($hasStatusColumn)
                    <select name="status" class="filter-sel">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(($filters['status'] ?? null) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(($filters['status'] ?? null) === 'nonaktif')>Nonaktif</option>
                    </select>
                @endif
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-hd">
            <div class="card-title">Daftar Pengguna</div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
        </div>
        <div class="card-body">
            @if ($users->isEmpty())
                <div class="empty-state">
                    <div class="es-icon">-</div>
                    <p>Belum ada pengguna.</p>
                </div>
            @else
                <div class="tbl-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            @if ($hasUsernameColumn)
                                <th>Username</th>
                            @endif
                            <th>Peran</th>
                            @if ($hasStatusColumn)
                                <th>Status</th>
                            @endif
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                @if ($hasUsernameColumn)
                                    <td>{{ $user->username ?: '-' }}</td>
                                @endif
                                <td><span class="badge badge-blue">{{ ucfirst($user->role) }}</span></td>
                                @if ($hasStatusColumn)
                                    <td>
                                        @if (($user->status ?? 'aktif') === 'aktif')
                                            <span class="badge badge-green">Aktif</span>
                                        @else
                                            <span class="badge badge-gray">Nonaktif</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        @if ($hasStatusColumn)
                                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    {{ ($user->status ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @endif
                                        @if ((int) $user->id !== (int) auth()->id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini?')">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
