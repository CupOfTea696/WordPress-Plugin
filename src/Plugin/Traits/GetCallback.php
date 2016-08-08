<?php

namespace CupOfTea\WordPress\Plugin\Traits;

// TODO: Extract to seperate Package.

use InvalidArgumentException;

trait GetCallback
{
    /**
     * Get a callable that is possibly callable on the class.
     * 
     * @param  string|callable  $callback
     * @return callable
     * @throws \InvalidArgumentException when the action can't be resolved to a callable.
     */
    protected function getCallback($callback)
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
