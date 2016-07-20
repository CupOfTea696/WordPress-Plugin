<?php

namespace CupOfTea\WordPress;

use CupOfTea\WordPress\Contracts\Plugin;
use CupOfTea\WordPress\Traits\GetCallback;
use CupOfTea\WordPress\Contracts\Component as ComponentContract;

abstract class Component implements ComponentContract
{
    use GetCallback;
    
    /**
     * The Plugin instance.
     * 
     * @var \CupOfTea\WordPress\Contracts\Plugin
     */
    protected $plugin;
    
    /**
     * {@inheritdoc}
     */
    final public function getPlugin()
    {
        return $this->plugin;
    }
    
    /**
     * {@inheritdoc}
     */
    final public function setPlugin(Plugin $plugin)
    {
        $this->plugin = $plugin;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function hook($name, $callback, $priority = 10, $accepted_args = 1)
    {
        return add_action($name, $this->getCallback($callback), $priority, $accepted_args);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function filter($name, $callback, $priority = 10, $accepted_args = 1)
    {
        return add_filter($name, $this->getCallback($callback), $priority, $accepted_args);
    }
    
    /**
     * Get the Callable for the hook.
     * 
     * @param  string|callable  $callback
     * @return callable
     * @throws \InvalidArgumentException when the action can't be resolved to a callable.
     */
    private function getCallable($callback)
    {
        if (is_string($callback) && is_callable($thisCallback = [$this, $callback])) {
			$callback = $thisCallback;
		}
		
		if (! is_callable($callback)) {
			throw new InvalidArgumentException('The provided callback is not callable.');
		}
        
        return $callback;
    }
}
