<?php

namespace Modules\Modulux\Resource;

class Table
{
    /**
     * @var TableColumn[]
     */
    protected array $_columns = [];

    /**
     * @var TableFilter[]
     */
    protected array $_filters = [];

    /**
     * @return TableAction[]
     */
    protected array $_actions = [];

    /**     *
     * @param array $columns
     */
    public function columns(array $columns): self
    {
        $this->_columns = $columns;

        return $this;
    }
    
    /**
     * @param array $columns
     */
    public function filters(array $filters): self
    {
        $this->_filters = $filters;

        return $this;
    }

    /**
     * @param array $actions
     */
    public function actions(array $actions): self
    {
        $this->_actions = $actions;

        return $this;
    }



    public static function make(array $columns): self
    {
        return (new self());
    }

    public function toArray(): array
    {
        return [
            'columns' => array_map(fn(TableColumn $column) => $column->toArray(), $this->_columns),
            'filters' => array_map(fn(TableFilter $filter) => $filter->toArray(), $this->_filters),
            'actions' => array_map(fn(TableAction $action) => $action->toArray(), $this->_actions),
        ];
    }
}
