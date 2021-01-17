<?php

trait abstractModuleHelperMgr
{
    /**
     * @param modManagerController $controller
     */
    public function loadMgrAbstractCssJs(modManagerController $controller)
    {
        $config = $this->getAbstractConfig();

        $controller->addCss($config['cssUrl'] . 'mgr/styles.css');
        $controller->addJavascript($config['jsUrl'] . 'mgr/abstractmodule.js');

        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/panel.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/formpanel.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/grid.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/localgrid.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/window.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/widgets/page.js');

        $controller->addJavascript($config['jsUrl'] . 'mgr/combo/select.local.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/combo/select.remote.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/combo/browser.js');

        $controller->addJavascript($config['jsUrl'] . 'mgr/misc/function.list.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/misc/component.list.js');
        $controller->addJavascript($config['jsUrl'] . 'mgr/misc/renderer.list.js');

        $controller->addHtml('<script type="text/javascript">abstractModule.config = ' . $this->modx->toJSON($config) . ';</script>');
    }

    /**
     * @param modManagerController $controller
     */
    public function loadMgrDefaultCssJs(modManagerController $controller)
    {
        $controller->addCss($this->cssUrl . 'mgr/default.css');
        $controller->addJavascript($this->jsUrl . 'mgr/' . $this::PKG_NAMESPACE . '.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/function.list.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/component.list.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/renderer.list.js');

        $config = $this->getConfig();
        $controller->addHtml('<script type="text/javascript">' . get_class($this) . '.config = ' . $this->modx->toJSON($config) . ';</script>');
    }
}
