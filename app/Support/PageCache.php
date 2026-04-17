<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageCache
{
    private const PREFIX = 'page-cache:html:';

    private const VERSION_KEY = 'page-cache:version';

    public static function enabled(): bool
    {
        $enabled = config('page-cache.enabled');

        if ($enabled === null) {
            return ! app()->environment('testing');
        }

        return (bool) $enabled;
    }

    public static function ttl(): int
    {
        return max((int) config('page-cache.ttl', 120), 1);
    }

    public static function key(Request $request): string
    {
        $user = $request->user();
        $sessionId = $request->hasSession() ? $request->session()->getId() : 'no-session';

        $parts = [
            self::version(),
            'GET',
            $request->fullUrl(),
            app()->getLocale(),
            $user?->getAuthIdentifier() ?? 'guest',
            $user?->role ?? 'guest',
            hash('sha256', $sessionId),
            self::assetVersion(),
        ];

        return self::PREFIX.hash('sha256', implode('|', $parts));
    }

    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, (string) Str::uuid());
    }

    private static function version(): string
    {
        $version = Cache::get(self::VERSION_KEY);

        if (! is_string($version) || $version === '') {
            $version = (string) Str::uuid();
            Cache::forever(self::VERSION_KEY, $version);
        }

        return $version;
    }

    private static function assetVersion(): string
    {
        $manifestPath = public_path('build/manifest.json');

        if (! is_file($manifestPath)) {
            return 'no-manifest';
        }

        return (string) filemtime($manifestPath);
    }
}
