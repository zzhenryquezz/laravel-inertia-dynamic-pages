<?php

namespace App\Resources;

use App\Models\Tag;
use Modules\Modulux\Resource\Form;
use Modules\Modulux\Resource\FormField;
use Modules\Modulux\Resource\Resource;
use Modules\Modulux\Resource\Table;
use Modules\Modulux\Resource\TableColumn;
use Modules\Modulux\Resource\TableColumnActions;

class TagResource extends Resource
{
    protected $model = Tag::class;
    protected $name = 'tags';

    public function getSidebarLink(): array
    {
        return [
            'title' => __('tags'),
            'icon' => 'tag',
            'href' => $this->routes['index']['path'],
        ];
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TableColumn::make('id')->header('ID')->size(10),
            TableColumn::make('name')->header('Name'),
            TableColumn::make('slug')->header('Slug'),
            TableColumn::make('color')->header('Color'),
            TableColumn::make('description')->header('Description'),
            TableColumnActions::actions([
                [
                    'label' => 'Edit',
                    'icon' => 'edit',
                    'href' => '/admin/tags/{row.id}/edit',
                ],
                [
                    'label' => 'Delete',
                    'icon' => 'trash',
                    'confirm' => true,
                    'request' => 'delete:/admin/tags/{row.id}',
                ],
            ])->size(100),
        ]);
    }

    public function form($form): Form
    {
        $fields = [
            FormField::make('name')
                ->component('text-field')
                ->rules('required|string|max:255|unique:tags,name,{{id}}')
                ->props([
                    'label' => __('name'),
                ]),
            FormField::make('slug')
                ->component('text-field')
                ->rules('required|string|max:255|unique:tags,slug,{{id}}')
                ->props([
                    'label' => __('slug'),
                ]),
            FormField::make('color')
                ->component('text-field')
                ->rules('nullable|string|max:255')
                ->props([
                    'label' => __('color'),
                ]),
            FormField::make('description')
                ->component('textarea-field')
                ->rules('nullable|string')
                ->props([
                    'label' => __('description'),
                ]),
        ];
        return $form->fields($fields);
    }
}
