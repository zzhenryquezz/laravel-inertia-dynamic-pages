<?php

namespace App\Dynamic;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

abstract class Controller
{
    /**
     * The model class for the resource.
     * @var string
     */
    protected string $modelClass;

    /**
     * The resource name (for routes, titles, etc).
     * @var string
     */
    protected string $resourceName;

    /**
     * Labels for the resource, can be overridden in child classes.
     * @var array
     */
    protected array $labels = [
        'display_name' => null, // Optional display name for the resource
        'display_name_plural' => null, // Optional plural display name
        'singular' => null,
        'plural' => null,
        'kebab_singular' => null,
        'kebab_plural' => null,
    ];

    public function __construct()
    {
        $defaults = [
            'display_name' => Str::ucfirst($this->resourceName),
            'display_name_plural' => Str::ucfirst(Str::plural($this->resourceName)),
            'singular' => $this->resourceName,
            'plural' => Str::plural($this->resourceName),
            'kebab_singular' => Str::kebab($this->resourceName),
            'kebab_plural' => Str::kebab(Str::plural($this->resourceName)),
        ];

        // Allow child classes to override any label by setting $labels property before parent::__construct()
        $this->labels = array_merge($defaults, array_filter($this->labels));
    }

    public function table(Table $table): Table
    {
        return $table;
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    public function index()
    {
        $model = $this->modelClass;
        $items = $model::query()->paginate(20);

        $table = new Table();

        $table
            ->actions([
                TableAction::make('edit')
                    ->label(__('edit'))
                    ->icon('Edit')
                    ->href('/' . $this->labels['kebab_plural'] . '/{row.id}/edit'),
                TableAction::make('delete')
                    ->label(__('delete'))
                    ->icon('Trash')
                    ->confirm(true)
                    ->request('delete:/' . $this->labels['kebab_plural'] . '/{row.id}'),
            ])
            ->columns([
                TableColumn::make('id')
                    ->size(10)
                    ->header(__('id'))
                    ->accessorKey('id'),
            ]);

        $table = $this->table($table);

        $tableArray = $table->toArray();

        return Inertia::render('Dynamic/Table', [
            'title' => $this->labels['display_name_plural'],
            'path' => '/' . $this->labels['kebab_plural'],
            'columns' => $tableArray['columns'],
            'actions' => $tableArray['actions'],
            'add-new-href' => '/' . $this->labels['kebab_plural'] . '/create',
            'breadcrumbs' => [
                [
                    'title' => __($this->labels['display_name_plural']),
                    'href' => '/' . $this->labels['kebab_plural'],
                ],
            ],
            'items' => $items->items(),
            'meta' => [
                'total' => $items->total(),
                'per_page' => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
            ],
        ]);
    }

    public function create()
    {
        $form = new Form();

        $form
            ->action('/' . $this->labels['kebab_plural'])
            ->method('POST')
            ->title(__('add_new'))
            ->description(__('create_entity', [$this->labels['display_name']]));

        $form = $this->form($form);

        return Inertia::render('Dynamic/Form', $form->toArray());
    }

    public function store(Request $request)
    {
        $form = new Form();
        $form = $this->form($form);

        $schema = $form->getLaravelRules();
        $data = $request->validate($schema);

        $model = $this->modelClass;
        $model::create($data);

        return redirect()
            ->route($this->labels['kebab_plural'] . '.index')
            ->with('success', __('created_successfully'));
    }

    public function edit($id)
    {
        $model = $this->modelClass;
        $item = $model::findOrFail($id);

        $form = new Form();

        $form
            ->action('/' . $this->labels['kebab_plural'] . '/' . $item->id)
            ->method('PATCH')
            ->title(__('edit'))
            ->description(__('edit_entity', [$this->labels['display_name']]))
            ->backUrl('/' . $this->labels['kebab_plural']);

        $form = $this->form($form);

        
        $form->setValues($item->toArray());
        
        return Inertia::render('Dynamic/Form', $form->toArray());
    }

    public function update(Request $request, $id)
    {
        
        $form = new Form();
        $form = $this->form($form);

        $schema = $form->getLaravelRules();

        $data = $request->validate($schema);

        $model = $this->modelClass;
        $item = $model::findOrFail($id);
        $item->update($data);

        return redirect()
            ->route($this->labels['kebab_plural'] . '.index')
            ->with('success', __('updated_successfully'));
    }

    public function destroy($id)
    {
        $model = $this->modelClass;
        $item = $model::findOrFail($id);
        $item->delete();

        return redirect()
            ->route($this->labels['kebab_plural'] . '.index')
            ->with('success', __('deleted_successfully'));
    }
}
