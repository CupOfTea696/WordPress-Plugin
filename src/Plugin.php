<?php

namespace CupOfTea\WordPress;

use ReflectionClass;
use CupOfTea\Package\Package;
use Illuminate\Container\Container;
use CupOfTea\WordPress\Plugin\MainComponent;
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
     * Plugin name.
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Plugin slug.
     * 
     * @var string
     */
    protected $slug;
    
    /**
     * Main plugin file WordPress uses.
     * 
     * @var string
     */
    protected $file;
    
    /**
     * Main Plugin Component.
     * 
     * @var string
     */
    protected $main;
    
    /**
     * Create a new Plugin instance.
     * 
     * @return void
     */
    final public function __construct($file)
    {
        $this->setFile($file);
        
        // Bind all components in the Plugin Container.
        foreach ($this->components as $component) {
            $this->singleton($component, function () use ($component) {
                $instance = new $component();
                
                if ($instance instanceof MainComponent) {
                    $this->setMain($component);
                }
                
                return $instance->setPlugin($this);
            });
        }
        
        // Boot the Plugin if it is bootable.
        if (method_exists($this, 'boot')) {
            $this->boot();
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug ?: sanitize_title($this->getName());
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMain($component)
    {
        $this->file = $name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getMain()
    {
        return $this->main;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMain($component)
    {
        $this->main = $component;
    }
    
    /**
     * {@inheritdoc}
     */
    public function main()
    {
        $this->make($this->getMain());
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
    
    /**
     * Get the Plugin Namespace.
     * 
     * @return string
     */
    final public function getNamespace()
	{
		return str_replace((new ReflectionClass($this))->getShortName(), '', get_class($this));
	}
}
