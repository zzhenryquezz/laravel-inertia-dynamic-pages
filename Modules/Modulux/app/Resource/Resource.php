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

    public function __construct()
    {
        $this->controller = new Controller($this->model);
        $this->controller->on('table', function (Table $table) {
            return $this->table($table);
        });
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoutes(): array
    {
        $plural = Str::plural($this->name);
        $kebabPlural = Str::kebab($plural);

        $routes = [
            [
                'path' => '/' . $kebabPlural,
                'name' => $plural . '.index',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->index(...$args)
            ],
            [
                'path' => '/' . $kebabPlural . '/create',
                'name' => $plural . '.create',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->create(...$args)
            ],
            [
                'path' => '/' . $kebabPlural . '/{id}',
                'name' => $plural . '.show',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->show(...$args)
            ],
            [
                'path' => '/' . $kebabPlural . '/{id}/edit',
                'name' => $plural . '.edit',
                'method' => 'GET',
                'callback' => fn(...$args) => $this->controller->edit(...$args)
            ],
            [
                'path' => '/' . $kebabPlural,
                'name' => $plural . '.store',
                'method' => 'POST',
                'callback' => fn(...$args) => $this->controller->store(...$args)
            ],
            [
                'path' => '/' . $kebabPlural . '/{id}',
                'name' => $plural . '.update',
                'method' => 'PUT',
                'callback' => fn(...$args) => $this->controller->update(...$args)
            ],
            [
                'path' => '/' . $kebabPlural . '/{id}',
                'name' => $plural . '.destroy',
                'method' => 'DELETE',
                'callback' => fn(...$args) => $this->controller->destroy(...$args)
            ]
        ];

        return $routes;
    }

    public function table(Table $table): Table
    {
        return $table;
    }

    public function form(Form $form): Form
    {
        return $form;
    }
}
