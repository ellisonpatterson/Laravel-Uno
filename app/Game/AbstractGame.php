<?php

namespace App\Game;

use App\Engine\GameEngine;

abstract class AbstractGame
{
    protected $engine;

    public function __construct(GameEngine &$engine)
    {
        $this->engine = $engine;
        $this->initialize();
    }

    public function initialize()
    {
        return true;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->engine, $name)) {
            return $this->engine->$name(...$arguments);
        }
    }

    public function &__get($property)
    {
        return $this->engine->__get($property);
    }

    public function __set($property, $value)
    {
        if (property_exists($this->engine, $property)) {
            return $this->engine->$property = $value;
        }
    }
}