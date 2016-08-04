<?php

namespace CupOfTea\WordPress\Plugin\Contracts;

interface RegistersBindingsInWordPress
{
    /**
     * Register the plugin with WordPress.
     * 
     * @return void
     */
    public function register();
}
