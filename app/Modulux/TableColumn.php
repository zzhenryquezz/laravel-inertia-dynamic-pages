<?php

namespace App\Dynamic;

class TableColumn
{
    public string $id = '';
    public string $header = '';
    public string $accessorKey = '';
    public int $size;

    public static function make(string $id): self
    {
        $column = new self();

        $column->id = $id;
        $column->header = ucfirst($id);
        $column->accessorKey = $id;

        return $column;
    }

    public function header(string $header): self
    {
        $this->header = $header;

        return $this;
    }
    
    public function accessorKey(string $accessorKey): self
    {
        $this->accessorKey = $accessorKey;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'header' => $this->header,
            'accessorKey' => $this->accessorKey,
            'size' => $this->size ?? 150,
        ];
    }


}
