<?php

namespace CupOfTea\WordPress;

abstract class AdminComponent extends Component
{
    public function createOptionsPage($pageTitle = null, $menuTitle = null, $capability = 'manage_options', $callback = 'getOptionsPage')
    {
        $pageTitle = $pageTitle ?: $this->plugin->getName();
        $menuTitle = $menuTitle ?: $pageTitle;
        
        return add_options_page($pageTitle, $menuTitle, $capability, $this->plugin->getSlug(), $this->getCallback($callback));
    }
    
    public function addOptionsLink($text = 'Settings')
    {
        return add_filter('plugin_action_links_' . plugin_basename($this->plugin->getFile()), function ($links) {
            return array_merge($links, [
                '<a href="' . admin_url('options-general.php?page=' . $this->plugin->getSlug()) . '">' . $text . '</a>',
            ]);
        });
    }
}
