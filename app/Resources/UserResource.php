<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Modulux\Resource\Form;
use Modules\Modulux\Resource\FormField;
use Modules\Modulux\Resource\Resource;
use Modules\Modulux\Resource\Table;
use Modules\Modulux\Resource\TableColumn;
use Modules\Modulux\Resource\TableColumnActions;

class UserResource extends Resource
{
    protected $model = User::class;
    protected $name = 'users';

    public function table(Table $table): Table
    {
        return $table->columns([
            TableColumn::make('id')
                ->header('ID')
                ->size(10),
            TableColumn::make('name')
                ->header('Name'),
            TableColumn::make('email')
                ->header('Email'),
            TableColumnActions::actions([
                [
                    'label' => 'Edit',
                    'icon' => 'edit',
                    'href' => '/admin/users/{row.id}/edit',
                ],
                [
                    'label' => 'Delete',
                    'icon' => 'trash',
                    'confirm' => true,
                    'request' => 'delete:/admin/users/{row.id}',
                ],
            ])->size(100),
        ]);
    }

    public function form($form): Form
    {
        // Define the form structure here
        return $form->fields([
            FormField::make('name')
                ->component('text-field')
                ->rules('required|string|max:255|min:2')
                ->props([
                    'label' => __('name'),
                ]),
            FormField::make('email')
                ->component('text-field')
                ->rules('required|email|max:255|min:5|unique:users,email,{{id}}')
                ->props([
                    'label' => __('email'),
                ]),
            FormField::make('password')
                ->component('text-field')
                ->rules('required|string|min:8|confirmed')
                ->props([
                    'label' => __('password'),
                    'type' => 'password',
                    'autocomplete' => 'new-password',
                ]),
            FormField::make('password_confirmation')
                ->component('text-field')
                ->rules('required|string|min:8')
                ->props([
                    'label' => __('confirm_password'),
                    'type' => 'password',
                    'autocomplete' => 'new-password',
                ]),
        ]);
    }

    public function getSidebarLink(): array
    {
        return [
            'title' => __('users'),
            'icon' => 'users',
            'href' => $this->routes['index']['path'],
        ];
    }
}
