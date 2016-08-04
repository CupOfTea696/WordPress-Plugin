<?php

namespace CupOfTea\WordPress\Plugin;

class Form
{
    public function text($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function textArea($id, $class = [], array $attributes = [])
    {
        $attributes = $this->getAttributes($class, $attributes);
        $properties = compact('id', 'attributes');
        
        return function () use ($properties) {
            $attributes['value'] = get_option($properties['id']);
            
            $this->compileField('<textarea id=":id" name=":name":attributes>:value</textarea>', $properties);
        }
    }
    
    public function password($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function bool($id, $class = [], array $attributes = [])
    {
        return $this->box('checkbox', $id, null, $class, $attributes);
    }
    
    public function checkbox($id, $class = [], array $attributes = [])
    {
        return $this->box(__FUNCTION__, $id, $id . '[]', $class, $attributes);
    }
    
    public function radio($id, $class = [], array $attributes = [])
    {
        return $this->box(__FUNCTION__, $id, null, $class, $attributes);
    }
    
    public function color($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function date($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function datetime($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function datetime_local($id, $class = [], array $attributes = [])
    {
        return $this->input('datetime-local', $id, $class, $attributes);
    }
    
    public function email($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function month($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function number($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function range($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function tel($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function time($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function url($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    public function week($id, $class = [], array $attributes = [])
    {
        return $this->input(__FUNCTION__, $id, $class, $attributes);
    }
    
    protected function input($type, $id, $class = [], array $attributes = [])
    {
        $attributes = $this->getAttributes($class, $attributes);
        $properties = compact('type', 'id', 'class', 'attributes');
        
        return $this->getInput('<input type=":type" id=":id" name=":name" value=":value":attributes>', $properties);
    }
    
    protected function box($type, $id, $name = null, $class = [], array $attributes = [])
    {
        $name = $name ?: $id;
        $attributes = $this->getAttributes($class, $attributes);
        $properties = compact('type', 'id', 'name', 'class', 'attributes');
        
        return $this->getBox('<input type=":type" id=":id" name=":name" value=":value":attributes>', $properties);
    }
    
    protected function getInput($tpl, $properties) {
        return function () use ($tpl, $properties) {
            $properties['value'] = get_option($properties['id']);
            
            $this->compileField($tpl, $properties);
        }
    }
    
    protected function getBox($tpl, $properties) {
        return function () use ($tpl, $properties) {
            $properties['attributes']['checked'] = (bool) get_option($properties['id']);
            
            $this->compileField($tpl, $properties);
        }
    }
    
    protected function compileField($field, $properties)
    {
        echo preg_replace_callback('/:([A-z_][A-z0-9_-]*)/', function ($matches) use ($properties) {
            if (! empty($properties[$matches[1]])) {
                if ($matches[1] == 'attributes') {
                    $str = '';
                    
                    foreach ($properties[$matches[1]] as $attribute => $value) {
                        if ($value === true) {
                            $str .= ' ' . $attribute;
                        } elseif ($value !== false) {
                            $str .= ' ' . $attribute . '="' . $value . '"';
                        }
                    }
                    
                    return $str;
                }
                
                return $properties[$matches[1]];
            }
            
            if ($matches[1] == 'name' && ! empty($properties['id'])) {
                return $properties['id'];
            }
            
            return '';
        }, $field);
    }
    
    protected function getAttributes($class, $attributes)
    {
        if (! isset($attributes['class'])) {
            $attributes['class'] = [];
        }
        
        if ($class) {
            if (is_string($attributes['class'])) {
                $attributes['class'] = explode(' ', $attributes['class']);
            }
            
            if (is_string($class)) {
                $class = explode(' ', $class);
            }
            
            $attributes['class'] = implode(' ', array_merge($attributes['class'], $class));
        }
        
        return $attributes;
    }
}
