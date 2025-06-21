<?php

namespace Modules\Modulux\Resource;

class TableColumnActions extends TableColumn
{
    public static function actions(array $actions)
    {
        return parent::make('actions')
            ->cell(
                TableCell::make('TableColumnActions')
                    ->props(['items' => $actions])
            );
    }

}
