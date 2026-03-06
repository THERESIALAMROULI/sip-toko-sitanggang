@extends('layouts.admin')

@section('title', 'Tambah Piutang')
@section('subtitle', 'Input manual piutang dinonaktifkan')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            Input manual piutang dinonaktifkan. Piutang dibuat otomatis dari transaksi kredit.
        </div>
        <a href="{{ route('receivables.index') }}" class="btn btn-secondary">Kembali ke daftar piutang</a>
    </div>
</div>
@endsection
