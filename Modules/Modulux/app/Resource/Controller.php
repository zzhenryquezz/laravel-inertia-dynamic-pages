<?php

namespace Modules\Modulux\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class Controller
{
    /**
     * The model class for the resource.
     * @var Model
     */
    protected $model;

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

    /**
     * Observers for the resource, can be used to handle events.
     * @var array
     */
    public array $routes = [];


    /**
     * Observers for the resource, can be used to handle events.
     * @var array
     */
    protected array $observers = [];

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function on(string $event, callable $callback): void
    {
        $this->observers[] = [
            'name' => $event,
            'handle' => $callback
        ];
    }

    public function table(Table $table): Table
    {
        return $table;
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    protected function resolveTable($table = null){
        $table = $table ?? new Table();

        $table
            ->columns([
                TableColumn::make('id')
                    ->size(10)
                    ->header(__('id'))
                    ->accessorKey('id'),
                TableColumnActions::actions([
                    [
                        'label' => 'Edit',
                        'icon' => 'edit',
                        'href' => '/users/{id}/edit',
                    ],
                    [
                        'label' => 'Delete',
                        'icon' => 'trash',
                        'confirm' => true,
                        'request' => 'delete:/users/{id}',
                    ],
                ])->size(100),
            ]);

        $table = $this->table($table);

        // handle obserers 
        collect($this->observers)->filter(fn($o) => $o['name'] === 'table')
            ->each(function ($observer) use ($table) {
                $table = $observer['handle']($table);
            });

        return $table;
    }

    protected function resolveForm(?Form $form = null): Form
    {
        $form = $form ?? new Form();

        $form = $this->form($form);

        // handle observers
        collect($this->observers)->filter(fn($o) => $o['name'] === 'form')
            ->each(function ($observer) use ($form) {
                $form = $observer['handle']($form);
            });

        return $form;
    }

    public function index()
    {
        $model = $this->model;
        $page = $model::query()->paginate(20);

        $table = $this->resolveTable();

        $table->items($page->items());

        return Inertia::render('modulux::Index', [
            'table' => $table->toArray(),
            'add-new-href' => $this->routes['create']['path'],
        ]);
    }

    public function create()
    {
        $form = new Form();

        $form
            ->action($this->routes['store']['path'])
            ->method('POST')
            ->title(__('add_new'))
            ->description(__('create_entity', [$this->labels['display_name']]))
            ->back($this->routes['index']['path'], __('back'));

        $form = $this->resolveForm($form);

        return Inertia::render('modulux::Create', [
            'form' => $form->toArray(),
        ]);
    }

    public function store()
    {
        try {
            $request = request();
            $form = $this->resolveForm();
    
            $schema = $form->getLaravelRules();
            $data = $request->validate($schema);
    
            $model = $this->model;
            $model::create($data);
    
            return redirect($this->routes['index']['path'])
                ->with('success', __('created_successfully'));
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('alerts', [
                    [
                        'type' => 'error',
                        'title' => __('error'),
                        'description' => __('failed_to_create'),
                    ],
                    [
                        'type' => 'error',
                        'title' => __('exception'),
                        'description' => $th->getMessage(),
                    ],
                ]);
        }
    }

    public function edit($id)
    {
        $model = $this->model;
        $item = $model::findOrFail($id);

        $form = new Form();

        $form
            ->action('/' . $this->labels['kebab_plural'] . '/' . $item->id)
            ->method('PATCH')
            ->title(__('edit'))
            ->description(__('edit_entity', [$this->labels['display_name']]))
            ->back($this->routes['index']['path'], __('back'));

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

        $model = $this->model;
        $item = $model::findOrFail($id);
        $item->update($data);

        return redirect()
            ->route($this->labels['kebab_plural'] . '.index')
            ->with('success', __('updated_successfully'));
    }

    public function destroy($id)
    {
        $model = $this->model;
        $item = $model::findOrFail($id);
        $item->delete();

        return redirect()
            ->route($this->labels['kebab_plural'] . '.index')
            ->with('success', __('deleted_successfully'));
    }
}
