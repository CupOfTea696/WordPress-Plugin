<?php

namespace CupOfTea\WordPress\Contracts;

use ArrayAccess;
use Illuminate\Contracts\Container\Container;

interface PluginInterface implements ArrayAccess, RegistersBindingsInWordPress, Container
{
    /**
     * Get the plugin name.
     * 
     * @return string
     */
    public function getName();
}
