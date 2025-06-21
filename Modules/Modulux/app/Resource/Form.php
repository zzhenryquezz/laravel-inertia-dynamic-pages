<?php

namespace Modules\Modulux\Resource;

class Form
{
    protected string $_action = ''; 
    protected string $_method = 'POST';
    protected ?string $_title = null;
    protected ?string $_description = null;
    protected ?array $_back = null;

    /**
     * @var FormField[]
     */
    protected array $_fields = [];

    public static function make(string $action): self
    {
        $form = new self();
        $form->_action = $action;
        return $form;
    }

    /**
     * @param FormField[] $fields
     */
    public function fields(array $fields): self
    {
        $this->_fields = $fields;

        return $this;
    }

    public function action(string $action): self
    {
        $this->_action = $action;

        return $this;
    }

    public function method(string $method): self
    {
        $this->_method = $method;

        return $this;
    }

    public function title(string $title): self
    {
        $this->_title = $title;

        return $this;
    }
    
    public function description(string $description): self
    {
        $this->_description = $description;

        return $this;
    }

    public function back(string $href, ?string $label): self
    {
        $this->_back = [
            'href' => $href,
            'label' => $label,
        ];

        return $this;
    }

    public function setFieldValue(string $fieldId, mixed $value): self
    {
        foreach ($this->_fields as $field) {
            if ($field->_id === $fieldId || $field->_name === $fieldId) {
                $field->value($value);
                break;
            }
        }

        return $this;
    }

    public function setValues(array $values): self
    {
        foreach ($values as $fieldId => $value) {
            $this->setFieldValue($fieldId, $value);
        }

        return $this;
    }

    public function getValues()
    {
        $values = [];

        foreach ($this->_fields as $field) {
            $values[$field->_name] = $field->_value;
        }

        return $values;
    
    }


    public function toArray(): array
    {
        return [
            'title' => $this->_title ?? null,
            'description' => $this->_description ?? null,
            'back' => $this->_back ? $this->_back : null,
            'action' => $this->_action ?? '',
            'method' => $this->_method ?? 'POST',
            'fields' => array_map(fn(FormField $f) => $f->toArray(), $this->_fields),
        ];
    }

    public function getLaravelRules(): array
    {
        $schema = []; 

        foreach ($this->_fields as $field) {
            $schema[$field->_name] = $field->_rules ?? 'nullable';
        }

        return $schema;
    }

}
