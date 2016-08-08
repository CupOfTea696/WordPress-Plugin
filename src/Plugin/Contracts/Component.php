<?php

namespace CupOfTea\WordPress\Plugin\Contracts;

use InvalidArgumentException;
use CupOfTea\WordPress\Contracts\Plugin;

interface Component extends RegistersBindingsInWordPress
{
    /**
     * Get the Plugin instance.
     * 
     * @return  \CupOfTea\WordPress\Contracts\Plugin
     */
    public function getPlugin();
    
    /**
     * Set the Plugin instance.
     * 
     * @param  \CupOfTea\WordPress\Contracts\Plugin  $plugin
     * @return \CupOfTea\WordPress\Contracts\Component
     */
    public function setPlugin(Plugin $plugin);
    
    /**
     * Get a plugin option from the Database.
     * 
     * @param  string  $option
     * @param  string  $default
     * @return mixed;
     */
    public function getOption($option, $default = null);
    
    /**
     * Get the main Plugin Component.
     * 
     * @return CupOfTea\WordPress\Contracts\Component
     */
    public function main();
    
    /**
     * Hook into a WordPress action.
     * 
     * @param  string  $name
     * @param  string|callable  $action
     * @param  int  $priority
     * @param  int  $accepted_args
     * @return true
     */
    public function hook($name, $action, $priority = 10, $accepted_args = 1);
    
    /**
     * Hook into a WordPress filter.
     * 
     * @param  string  $name
     * @param  string|callable  $action
     * @param  int  $priority
     * @param  int  $accepted_args
     * @return true
     */
    public function filter($name, $action, $priority = 10, $accepted_args = 1);
}
