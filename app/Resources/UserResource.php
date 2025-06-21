<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
}
