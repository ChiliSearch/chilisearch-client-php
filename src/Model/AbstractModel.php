<?php

namespace SearChili\Model;

abstract class AbstractModel
{
    protected $_attributes;

    public function __construct($attributes)
    {
        $this->_attributes = $attributes;
    }

    public function __get($name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    public function toArray()
    {
        return $this->_attributes;
    }
}
