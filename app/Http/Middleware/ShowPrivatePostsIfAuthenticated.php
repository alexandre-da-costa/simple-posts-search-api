<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowPrivatePostsIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $this->authenticateIfPossible($request);
        $this->scopeQueryIfUnauthenticated();
        return $next($request);
    }

    public function authenticateIfPossible(Request $request): void
    {
        if ($request->bearerToken())
            auth()->shouldUse('sanctum');
    }

    public function scopeQueryIfUnauthenticated(): void
    {
        if (!auth()->check())
            Post::addGlobalScope(function ($query) {
                $query->where('is_public', true);
            });
    }
}
