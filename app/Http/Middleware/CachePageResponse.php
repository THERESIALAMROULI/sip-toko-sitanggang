<?php

namespace App\Http\Middleware;

use App\Support\PageCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CachePageResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! PageCache::enabled()) {
            return $next($request);
        }

        if (! $this->isReadRequest($request)) {
            $response = $next($request);

            if ($response->getStatusCode() < 500) {
                PageCache::flush();
            }

            return $response;
        }

        if (! $this->shouldReadFromCache($request)) {
            return $next($request);
        }

        $cacheKey = PageCache::key($request);
        $cachedPage = Cache::get($cacheKey);

        if (is_array($cachedPage) && isset($cachedPage['content'])) {
            return response($cachedPage['content'], (int) ($cachedPage['status'] ?? 200))
                ->header('Content-Type', 'text/html; charset=UTF-8')
                ->header('Cache-Control', 'private, max-age=0, must-revalidate')
                ->header('X-Page-Cache', 'HIT');
        }

        $response = $next($request);

        if ($this->isCacheableResponse($request, $response)) {
            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
            ], PageCache::ttl());

            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    private function isReadRequest(Request $request): bool
    {
        return $request->isMethod('GET');
    }

    private function shouldReadFromCache(Request $request): bool
    {
        return $request->user() !== null
            && ! $request->expectsJson()
            && ! $request->ajax()
            && ! $request->headers->has('HX-Request')
            && ! $this->hasFlashData($request);
    }

    private function isCacheableResponse(Request $request, Response $response): bool
    {
        if (! $response->isOk()) {
            return false;
        }

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return false;
        }

        if (! str_contains((string) $response->headers->get('Content-Type', 'text/html'), 'text/html')) {
            return false;
        }

        if ($this->hasFlashData($request)) {
            return false;
        }

        return is_string($response->getContent()) && $response->getContent() !== '';
    }

    private function hasFlashData(Request $request): bool
    {
        if (! $request->hasSession()) {
            return false;
        }

        $session = $request->session();

        return $session->has('success')
            || $session->has('error')
            || $session->has('status')
            || $session->has('errors')
            || $session->has('_old_input');
    }
}
