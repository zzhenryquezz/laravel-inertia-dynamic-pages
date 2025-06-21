<?php

namespace Modules\Modulux\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Modulux\Registry\ResourceRegistry;

class ModuluxMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $resources = ResourceRegistry::all(); 

        Inertia::share([
            'alerts' => session('alerts', []),
            'modulux' => [
                'resources' => collect($resources)
                    ->map(fn ($r) => [
                        'name' => $r->getName(),
                    ])
                    ->values()
                    ->all(),
                'sidebar' => [
                    'links' => collect($resources)
                        ->map(fn ($r) => $r->getSidebarLink())
                        ->values()
                        ->all(),
                ]
            ],
        ]);

        return $next($request);
    }
}
