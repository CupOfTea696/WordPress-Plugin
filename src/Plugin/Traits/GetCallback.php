<?php

namespace CupOfTea\WordPress\Traits;

// TODO: Extract to seperate Package.

trait GetCallback
{
    /**
     * Get a callable that is possibly callable on the class.
     * 
     * @param  string|callable  $callback
     * @return callable
     * @throws \InvalidArgumentException when the action can't be resolved to a callable.
     */
    private function getCallback($callback)
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