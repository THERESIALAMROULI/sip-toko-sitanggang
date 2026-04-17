<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Support\PageCache;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('page-cache:clear', function () {
    PageCache::flush();
    $this->info('Page cache berhasil dikosongkan.');
})->purpose('Clear cached HTML pages');
