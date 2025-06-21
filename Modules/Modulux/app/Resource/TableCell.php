<?php

namespace Modules\Modulux\Resource;

class TableCell
{
    public string $component = 'span';
    public array $props = [];

    public static function make(string $component = 'span'): self
    {
        $cell = new self();
        $cell->component = $component;

        return $cell;
    }

    public function props(array $props): self
    {
        $this->props = $props;

        return $this;
    }
    
    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'props' => $this->props,
        ];
    }
    
}
