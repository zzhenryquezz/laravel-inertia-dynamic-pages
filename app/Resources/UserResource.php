<?php

namespace App\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
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

    public function getSidebarLink(): array
    {
        return [
            'title' => __('users'),
            'icon' => 'users',
            'href' => $this->routes['index']['path'],
        ];
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TableColumn::make('id')->header('ID')->size(10),
            TableColumn::make('name')->header('Name'),
            TableColumn::make('email')->header('Email'),
            TableColumnActions::actions([
                [
                    'label' => 'Edit',
                    'icon' => 'edit',
                    'href' => '/admin/users/{row.id}/edit',
                ],
                [
                    'label' => 'Reset Password',
                    'icon' => 'key',
                    'href' => '/admin/users/{row.id}/reset-password',
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
        $fields = [
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
        ];
            

        // operation is edit or update
        if (in_array($form->operation, ['edit', 'update'])) {
            return $form->fields($fields);
        }

        $fields[] = FormField::make('password')
            ->component('text-field')
            ->rules('required|string|min:8|confirmed')
            ->props([
                'label' => __('password'),
                'type' => 'password',
                'autocomplete' => 'new-password',
            ]);

        $fields[] = FormField::make('password_confirmation')
            ->component('text-field')
            ->rules('required|string|min:8')
            ->props([
                'label' => __('confirm_password'),
                'type' => 'password',
                'autocomplete' => 'new-password',
            ]);

        return $form->fields($fields);
    }

    
    public function resetPasswordForm($id)
    {
        $form = Form::make('reset-password')
            ->action("/admin/users/{$id}/reset-password")
            ->method('POST')
            ->title(__('reset_password'))
            ->description(__('reset_password_description'))
            ->back($this->routes['index']['path'], __('back'));

        $form->fields([
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

        return Inertia::render('modulux::Form', [
            'form' => $form->toArray(),
            'title' => __('reset_password'),
            'description' => __('reset_password_description'),
        ]);
        
    }

    public function resetPassword($id)
    {
        try {
            $request = request();
            $payload = $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ]);
    
            $user = User::findOrFail($id);
            $user->password = Hash::make($payload['password']);
            $user->save();        
    
            return redirect($this->routes['index']['path'])
                ->with('success', __('password_reset_success'));
        
        } catch (\Throwable $th) {
              return redirect()
                ->back()
                ->withInput()
                ->with('alerts', [
                    [
                        'type' => 'error',
                        'title' => __('error'),
                        'description' => __('failed_to_reset_password'),
                    ],
                    [
                        'type' => 'error',
                        'title' => __('exception'),
                        'description' => $th->getMessage(),
                    ],
                ]);
        }
    }

    public function boot(){
        parent::boot(); 

        $this->routes['reset-password'] = [
            'path' => '/admin/users/{id}/reset-password',
            'method' => 'POST',
            'name' => 'users.reset-password',          
            'callback' => fn($id) => $this->resetPassword($id),
        ];

        $this->routes['reset-password-form'] = [
            'path' => '/admin/users/{id}/reset-password',
            'method' => 'GET',
            'name' => 'users.reset-password-form',
            'callback' => fn($id) => $this->resetPasswordForm($id),
        ];
    }
    
}
