<?php

namespace CupOfTea\WordPress\Contracts;

use Illuminate\Contracts\Container\Container;
use CupOfTea\WordPerss\Plugin\Contracts\RegistersBindingsInWordPress;

interface Plugin extends RegistersBindingsInWordPress, Container
{
    /**
     * Get the Plugin name.
     * 
     * @return string
     */
    public function getName();
    
    /**
     * Set the Plugin name.
     * 
     * @return void
     */
    public function setName($name);
    
    /**
     * Get the Plugin slug.
     * 
     * @return string
     */
    public function getSlug();
    
    /**
     * Set the Plugin slug.
     * 
     * @return void
     */
    public function setSlug($slug);
    
    /**
     * Get the main Plugin file.
     * 
     * @return string
     */
    public function getFile();
    
    /**
     * Set the main Plugin file.
     * 
     * @return void
     */
    public function setFile($name);
    
    /**
     * Get the main Plugin Component class.
     * 
     * @return string
     */
    public function getMain();
    
    /**
     * Set the main Plugin Component class.
     * 
     * @return void
     */
    public function setMain($component);
    
    /**
     * Get the main Plugin Component.
     * 
     * @return CupOfTea\WordPress\Contracts\Component
     */
    public function main();
}
