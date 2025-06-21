<?php

namespace Modules\Modulux\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resource
{
    /**
     * The model class associated with the resource.
     *
     * @var Model
     */
    protected $model;

    /**
     * The name of the resource, used for routes and labels.
     *
     * @var string
     */
    protected $name;

    /**
     * The controller class for the resource.
     *
     * @var Contrloller
     */
    protected $controller;
    
    /**
     * Admin path
     *
     * @var string
     */
    protected string $path;

     /**
     * Observers for the resource, can be used to handle events.
     * @var array
     */
    public array $routes = [];

    public function __construct()
    {
        $this->path = config('modulux.dashboard.path', 'admin');

        $this->controller = new Controller($this->model);

        $this->controller->on('table', function (Table $table) {
            return $this->table($table);
        });
        
        $this->controller->on('form', function (Form $form) {
            return $this->form($form);
        });

        $this->routes = [
            'index' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)),
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->index(...$args)
            ],
            'create' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)) . '/create',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->create(...$args)
            ],
            'store' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)),
                'method' => 'POST',
                'callback' => fn(...$args) => $this->controller->store(...$args)
            ],
            'edit' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)) . '/{id}/edit',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->edit(...$args)
            ],
            'update' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)) . '/{id}',
                'method' => 'PATCH',
                'callback' => fn(...$args) => $this->controller->update(...$args)
            ],
            'destroy' => [
                'path' => '/' . $this->path . '/' . Str::kebab(Str::plural($this->name)) . '/{id}',
                'method' => 'DELETE',
                'callback' => fn(...$args) => $this->controller->destroy(...$args)
            ],
        ];

        $this->controller->routes = $this->routes;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function table(Table $table): Table
    {
        return $table;
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    public function getSidebarLink(): array
    {
        return [
            'title' => $this->getName(),
            'icon' => 'Circle',
            'href' => $this->routes['index']['path'],
        ];
    }
}
