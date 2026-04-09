<?php

// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Foundation\Inspiring;
// Mengimpor class atau helper yang dibutuhkan pada file ini.
use Illuminate\Support\Facades\Artisan;

// Baris ini merupakan bagian dari logika proses pada file ini.
Artisan::command('inspire', function () {
    // Baris ini merupakan bagian dari logika proses pada file ini.
    $this->comment(Inspiring::quote());
// Baris ini merupakan bagian dari logika proses pada file ini.
})->purpose('Display an inspiring quote');
