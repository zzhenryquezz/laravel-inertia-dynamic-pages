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
        $path = config('modulux.dashboard.path', 'admin');
        
        foreach (ResourceRegistry::all() as $resource) {

            $routes = $resource->getRoutes();
            // add index, show, create, edit, store, update, destroy methods to the resource
            foreach ($routes as $route) {
                $method = strtolower($route['method']); 
                $callback = $route['callback'];

                 Route::{$method}($path . $route['path'], $callback)
                    ->name($path . "." . $route['name'])
                    ->middleware('web')
                    ->middleware(ModuluxMiddleware::class);
            }
        }
    }
}
