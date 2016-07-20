<?php

namespace CupOfTea\WordPress\Contracts;

interface RegistersBindingsInWordPress
{
    /**
     * Register the plugin with WordPress.
     * 
     * @return void
     */
    public function register();
}
