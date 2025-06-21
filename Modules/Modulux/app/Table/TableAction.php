<?php

namespace App\Dynamic;

class TableAction
{
    public string $id;
    public string $type;
    public ?string $label;
    public ?string $icon;
    public ?string $href;
    public ?bool $confirm;
    public ?string $request;

    public static function make($id): self
    {
        $action = new self();

        $action->id = $id;

        return $action;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function href(string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function confirm(bool $confirm): self
    {
        $this->confirm = $confirm;

        return $this;
    }

    public function request(string $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id ?? null,
            'label' => $this->label ?? null,
            'type' => $this->type ?? null,
            'icon' => $this->icon ?? null,
            'href' => $this->href ?? null,
            'confirm' => $this->confirm ?? null,
            'request' => $this->request ?? null,
        ], fn($val) => !is_null($val));
    }
}
