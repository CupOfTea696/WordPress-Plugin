<?php

namespace CupOfTea\WordPress;

abstract class AdminComponent extends Component
{
    public function optionsPage($pageTitle, $menuTitle = null, $capability = 'manage_options', $callback = 'getSettingsPage')
    {
        return add_options_page($pageTitle, $menuTitle ?: $pageTitle, $capability, sanitize_title($menuTitle), $this->getCallback($callback));
    }
}
