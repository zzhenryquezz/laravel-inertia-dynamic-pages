<?php

namespace Modules\Modulux\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Modulux\Http\Middleware\ModuluxMiddleware;
use Modules\Modulux\Registry\ResourceRegistry;

class ResourceServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void {
         // Register all classes in app/Resources with the ResourceRegistry
        foreach (glob(base_path('app/Resources/*.php')) as $file) {
            $class = 'App\\Resources\\' . basename($file, '.php');
            if (class_exists($class)) {
                ResourceRegistry::register(new $class());
            }
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    public function boot(): void
    {
        foreach (ResourceRegistry::all() as $resource) {
            // add index, show, create, edit, store, update, destroy methods to the resource
            foreach ($resource->routes as $route) {
                $method = strtolower($route['method']); 
                $callback = $route['callback'];

                 Route::{$method}($route['path'], $callback)
                    ->middleware('web')
                    ->middleware(ModuluxMiddleware::class);
            }
        }
    }
}
