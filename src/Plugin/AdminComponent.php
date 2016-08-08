<?php

namespace CupOfTea\WordPress\Plugin;

abstract class AdminComponent extends Component
{
    protected $forms = [];
    
    protected $sections = [];
    
    public function __construct()
    {
        $this->hook('whitelist_options', 'whitelistCustomOptionsPage', 11);
    }
    
    public function createOptionsPage($name = '', $pageTitle = null, $menuTitle = null, $capability = 'manage_options', $callback = 'getOptionsPage')
    {
        $pageTitle = $this->value($pageTitle, $this->getPlugin()->getName());
        $menuTitle = $this->value($menuTitle, $pageTitle);
        $slug = $this->slug([$this->getPlugin()->getSlug(), $name]);
        
        $this->forms[$slug] = new Form();
        
        return add_options_page($pageTitle, $menuTitle, $capability, $slug, $this->getCallback($callback));
    }
    
    public function addOptionsLink($page = '', $text = 'Settings')
    {
        return add_filter('plugin_action_links_' . plugin_basename($this->getPlugin()->getFile()), function ($links) use ($page, $text) {
            return array_merge($links, [
                '<a href="' . admin_url('options-general.php?page=' . $this->slug([$this->getPlugin()->getSlug(), $page])) . '">' . $text . '</a>',
            ]);
        });
    }
    
    public function whitelistCustomOptionsPage($options)
    {
        foreach ($this->sections as $page => $sections) {
            foreach ($sections as $section) {
                if (! empty($options[$section])) {
                    foreach ($options[$section] as $option) {
                        $options[$page][] = $option;
                    }
                }
            }
        }
        
        return $options;
    }
    
    public function addSettingsSection($title = '', $description = '', $page = '')
    {
        $group = $this->slug([
            str_replace('-', '_', $this->getPlugin()->getSlug()),
            $page,
            str_replace('-', '_', sanitize_title($title)),
            'section',
        ]);
        $page = $this->slug([$this->getPlugin()->getSlug(), $page]);
        
        add_settings_section(
            $group,
            $title,
            function ($args) use ($description) {
                return preg_replace_callback('/:([A-z_][A-z0-9_-]*)/', function ($matches) use ($args) {
                    if (! empty($args[$matches[1]])) {
                        return $args[$matches[1]];
                    }
                    
                    return $matches[0];
                }, $description);
            },
            $page
        );
        
        if ($group != $page) {
            $this->sections[$page][$group] = $group;
        }
    }
    
    public function registerSetting($setting, $label, array $field = [], $group = '', $page = '')
    {
        $form = $this->forms[
            $this->slug([
                $this->getPlugin()->getSlug(),
                $page,
            ])
        ];
        
        $setting = $this->slug([
            str_replace('-', '_', $this->getPlugin()->getSlug()),
            $setting,
        ]);
        
        $group = $this->slug([
            str_replace('-', '_', $this->getPlugin()->getSlug()),
            $page,
            str_replace('-', '_', sanitize_title($group)),
            'section',
        ]);
        
        add_option($setting);
        
        add_settings_field(
            $setting,
            $label,
            $this->getField($form, $setting, $field),
            $this->slug([$this->getPlugin()->getSlug(), $page]),
            $group,
            ['label_for' => $setting]
        );
        
        register_setting(
            $group,
            $setting,
            function ($input) {
                return $input;
            }
        );
    }
    
    protected function view($name, $page = '', $data = [])
    {
        $viewDirs = [
            dirname($this->getPlugin()->getFile()) . '/views/',
            dirname(__DIR__) . '/views/',
        ];
        
        $views = [
            ltrim($name, '/'),
            str_replace('.', '/', $name) . '.php',
        ];
        
        $page = $this->slug([$this->getPlugin()->getSlug(), $page]);
        
        foreach ($viewDirs as $viewDir) {
            foreach ($views as $view) {
                $path = $viewDir . $view;
                
                if (file_exists($path)) {
                    $view = function($__view_path, $__data) {
                        extract($__data);
                        
                        return include $__view_path;
                    };
                    
                    $data['__plugin'] = $this->getPlugin();
                    $data['__page'] = $page;
                    
                    if (! isset($data['plugin'])) {
                        $data['plugin'] = $data['__plugin'];
                    }
                    
                    if (! isset($data['page'])) {
                        $data['page'] = $page;
                    }
                    
                    return $view($path, $data);
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
        
        $type = $this->array_get('type', $field, 'text');
        $class = $this->array_get('class', $field, []);
        $attributes = $this->array_get('attributes', $field, []);
        
        return $form->$type($setting, $class, $attributes);
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
