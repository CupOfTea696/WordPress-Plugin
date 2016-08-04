<?php

namespace CupOfTea\WordPress\Plugin\Contracts;

use InvalidArgumentException;

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
     * Hook into a WordPress action.
     * 
     * @param  string  $name
     * @param  string|callable  $action
     * @param  int  $priority
     * @param  int  $accepted_args
     * @return true
     */
    protected function hook($name, $action, $priority = 10, $accepted_args = 1);
    
    /**
     * Hook into a WordPress filter.
     * 
     * @param  string  $name
     * @param  string|callable  $action
     * @param  int  $priority
     * @param  int  $accepted_args
     * @return true
     */
    protected function filter($name, $action, $priority = 10, $accepted_args = 1);
}
