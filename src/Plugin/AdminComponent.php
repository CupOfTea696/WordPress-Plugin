<?php

namespace CupOfTea\WordPress\Plugin;

abstract class AdminComponent extends Component
{
    protected $options = [];
    
    protected function createOptionsPage($name = 'settings', $pageTitle = null, $menuTitle = null, $capability = 'manage_options', $callback = 'getOptionsPage')
    {
        $pageTitle = $this->value($pageTitle, $this->plugin->getName());
        $menuTitle = $this->value($menuTitle, $pageTitle);
        $slug = $this->slug($this->plugin->getSlug(), $name);
        
        $this->options[$slug] = new Form();
        
        return add_options_page($pageTitle, $menuTitle, $capability, $slug, $this->getCallback($callback));
    }
    
    protected function addSettingsSection($title = '', $description = '', $page = 'settings')
    {
        add_settings_section(
            $this->slug([
                $this->plugin->getSlug(),
                $page,
                sanitize_title($title),
                'section',
            ]),
            $title,
            function ($args) use ($description) {
                return preg_replace_callback('/:([A-z_][A-z0-9_-]*)/', function ($matches) use ($args) {
                    if (! empty($args[$matches[1]])) {
                        return $args[$matches[1]];
                    }
                    
                    return $matches[0];
                }, $description);
            },
            $this->plugin->getSlug() . '_' . $page
        );
    }
    
    protected function registerSetting($setting, $label, array $field = [], $group = '', $page = 'settings')
    {
        $setting = $this->slug([
            $this->plugin->getSlug(),
            $setting,
        ]);
        
        register_setting(
            $this->slug([
                $this->plugin->getSlug(),
                $page,
                sanitize_title($group),
                'section',
            ]),
            $setting
        );
        
        add_settings_field(
            $setting,
            $label,
            $this->getField($field),
            $this->plugin->getSlug() . '_' . $page
        );
    }
    
    protected function addOptionsLink($text = 'Settings')
    {
        return add_filter('plugin_action_links_' . plugin_basename($this->plugin->getFile()), function ($links) {
            return array_merge($links, [
                '<a href="' . admin_url('options-general.php?page=' . $this->plugin->getSlug()) . '">' . $text . '</a>',
            ]);
        });
    }
    
    protected function view($name)
    {
        $viewDirs = [
            dirname($this->getPlugin()->getFile()) . '/views/',
            dirname(__DIR__) . '/views/',
        ];
        
        $views = [
            ltrim($name, '/'),
            str_replace('.', '/', $name) . '.php',
        ];
        
        foreach ($viewDirs as $viewDir) {
            foreach ($views as $view) {
                $path = $viewDir . $view;
                
                if (file_exists($path)) {
                    return include $path;
                }
            }
        }
        
        throw new InvalidArgumentException("The view [$name] could not be found.");
    }
    
    private function slug($parts)
    {
        return implode('_', array_filter($parts));
    }
    
    private function getField($form, $setting, $field)
    {
        if (! $field) {
            return $form->text($setting);
        }
        
        $type = $this->arary_get('type', $field, 'text');
        $class = $this->arary_get('class', $field, []);
        $attributes = $this->arary_get('attributes', $field, []);
        
        $form->$type($setting, $class, $attributes);
    }
    
    private function value($value, $fallback = null)
    {
        if (is_callable($value)) {
            return $value();
        }
        
        return $value ?: $fallback;
    }
    
    private function array_get($key, $array, $fallback = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        return $fallback;
    }
}
