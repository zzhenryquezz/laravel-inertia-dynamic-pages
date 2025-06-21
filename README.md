# Laravel Starter Kit: Dynamic Tables & Forms Experiment

This project is an experimental Laravel starter kit designed to explore dynamic generation of tables and forms using [Inertia.js](https://inertiajs.com/), [Tailwind CSS](https://tailwindcss.com/), and [shadcn/ui](https://ui.shadcn.com/). The goal is to create a developer experience similar to [Filament](https://filamentphp.com/), but leveraging the flexibility of Inertia and modern frontend tooling.

## Key Features

- **Dynamic Table Generation:** Easily define and render data tables with customizable columns, filters, and actions.
- **Dynamic Form Generation:** Build forms dynamically from configuration or model definitions, supporting validation and custom layouts.
- **Inertia.js Integration:** Enjoy a modern, single-page application experience with server-driven UI updates.
- **Tailwind CSS Styling:** Rapidly style your UI with utility-first CSS classes.
- **shadcn/ui Components:** Use accessible, customizable React components for a polished look and feel.

## Inspiration

This project draws inspiration from Filament, aiming to provide a similar developer experience for building admin panels and dashboards, but with a stack based on Inertia.js and modern frontend tools.

## Status

This is an early-stage experiment. Features and APIs are subject to change as the project evolves.

## Getting Started

1. **Clone the repository:**
   ```sh
   git clone <repo-url>
   cd laravel-starter-kit
   ```
2. **Install dependencies:**
   ```sh
   composer install
   npm install
   ```
3. **Run migrations:**
   ```sh
   php artisan migrate
   ```
4. **Enable Modulux module:**
   ```sh
   php artisan module:enable Modulux
   ```
5. **Start the development server:**
   ```sh
   composer run dev
   ```

## Creating a Custom Resource (with Table, Form, and Dashboard Link)

To create a custom resource that supports dynamic table and form generation and appears in the dashboard sidebar, follow these steps:

1. **Create a Resource Class:**
   - Add a new file in `app/Resources/`, e.g., `ExampleResource.php`.
   - Extend the `Modules\Modulux\Resource\Resource` class.
   - Define your model, resource name, table columns, form fields, and sidebar link.
   - Example:

   ```php
   namespace App\Resources;

   use App\Models\Example;
   use Modules\Modulux\Resource\Form;
   use Modules\Modulux\Resource\FormField;
   use Modules\Modulux\Resource\Resource;
   use Modules\Modulux\Resource\Table;
   use Modules\Modulux\Resource\TableColumn;
   use Modules\Modulux\Resource\TableColumnActions;

   class ExampleResource extends Resource
   {
       protected $model = Example::class;
       protected $name = 'examples';

       public function getSidebarLink(): array
       {
           return [
               'title' => __('examples'),
               'icon' => 'box',
               'href' => $this->routes['index']['path'],
           ];
       }

       public function table(Table $table): Table
       {
           return $table->columns([
               TableColumn::make('id')->header('ID')->size(10),
               TableColumn::make('name')->header('Name'),
               TableColumnActions::actions([
                   [
                       'label' => 'Edit',
                       'icon' => 'edit',
                       'href' => '/admin/examples/{row.id}/edit',
                   ],
                   [
                       'label' => 'Delete',
                       'icon' => 'trash',
                       'confirm' => true,
                       'request' => 'delete:/admin/examples/{row.id}',
                   ],
               ])->size(100),
           ]);
       }

       public function form($form): Form
       {
           $fields = [
               FormField::make('name')
                   ->component('text-field')
                   ->rules('required|string|max:255|unique:examples,name,{{id}}')
                   ->props([
                       'label' => __('name'),
                   ]),
           ];
           return $form->fields($fields);
       }
   }
   ```

2. **Resource Registration:**
   - The `ResourceServiceProvider` will automatically register all resources in `app/Resources/`.

3. **Sidebar/Dashboard Link:**
   - The `getSidebarLink()` method defines how your resource appears in the dashboard sidebar.

4. **Table and Form Generation:**
   - The `table()` method defines columns and actions for the resource's data table.
   - The `form()` method defines fields and validation for create/edit forms.

5. **Routes and CRUD:**
   - Routes for index, create, edit, store, update, and delete are automatically registered based on your resource definition.

---

See `TagResource` and `UserResource` in `app/Resources/` for real examples.

