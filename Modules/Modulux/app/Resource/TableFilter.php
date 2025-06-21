<?php

namespace Modules\Modulux\Resource;

class TableFilter
{
    public string $id;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
