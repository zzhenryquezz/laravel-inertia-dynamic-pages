<?php

namespace Modules\Modulux\Resource;

class FormField
{
    public string $_id = '';
    public string $_name = '';
    public $_value = '';
    public string $_component = 'CFormTextField';
    public string $_rules = '';
    public array $_props = [];

    public static function make(string $id): self
    {
        $field = new self();

        $field->_id = $id;
        $field->_name = $id;

        return $field;
    } 

    public function name(string $name): self
    {
        $this->_name = $name;

        return $this;
    }

    public function component(string $component): self
    {
        $shorts = [
            'text' => 'CFormTextField',
            'select' => 'CFormSelect',
        ];
        
        if (isset($shorts[$component])) {
            $component = $shorts[$component];
        }

        $this->_component = $component;

        return $this;
    }

    public function props(array $props): self
    {
        $this->_props = $props;

        return $this;
    }

    public function value($value): self
    {
        $this->_value = $value;

        return $this;
    }

    public function rules(string $rules): self
    {
        $this->_rules = $rules;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->_name,
            'component' => $this->_component,
            'value' => $this->_value,
            'props' => $this->_props,
            'rules' => $this->_rules,
        ];
    }


}
