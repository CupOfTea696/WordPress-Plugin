<?php

namespace CupOfTea\WordPress\Plugin;

use BadMethodCallException;

abstract class MainComponent extends Component
{
    final public function __call($method, $args)
    {
        $plugin = $this->getPlugin();
        $namespace = $plugin->getNamespace();
        $component = ucfirst(str_replace('_', '\\', $method));
        
        if ($plugin->bound($namespace . '\\' . $component)) {
            return $plugin->make($namespace . '\\' . $component);
        }
        
        if ($plugin->bound($component)) {
            return $plugin->make($component);
        }
        
        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
