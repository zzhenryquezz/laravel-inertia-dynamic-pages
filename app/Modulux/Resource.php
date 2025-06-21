<?php

namespace App\Dynamic;

use Illuminate\Database\Eloquent\Model;

class Resource
{
    protected Table $_table;
    protected Model $_model;

    public static function make(string $name, string $modelClass, array $fields): self
    {
        $instance = new self();
        $instance->_table = (new Table())->columns($fields);
        $instance->_model = new $modelClass();

        return $instance;
    }
}
