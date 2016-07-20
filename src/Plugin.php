<?php

namespace CupOfTea\WordPress;

use CupOfTea\Package\Package;
use Illuminate\Container\Container;
use CupOfTea\WordPress\Contracts\Plugin as PluginContract;

abstract class Plugin extends Container implements PluginContract
{
    use PACKAGE;
    
    /**
     * Package Name.
     *
     * @const string
     */
    const PACKAGE = 'CupOfTea/WordPress-Plugin';
    
    /**
     * Package Version.
     *
     * @const string
     */
    const VERSION = '0.0.0';
    
    /**
     * Plugin Components.
     * 
     * @var array
     */
    protected $components = [];
    
    /**
     * Bind all components in the Plugin Container.
     * 
     * @return void
     */
    public function __construct()
    {
        foreach ($this->components as $component) {
            $this->singleton($component, function () use ($component) {
                return (new $component())->setPlugin($this);
            });
        }
    }
    
    /**
     * Register all Components with WordPress.
     * 
     * @return void
     */
    public function register()
    {
        foreach ($this->components as $component) {
            $this->make($component)->register();
        }
    }
}
