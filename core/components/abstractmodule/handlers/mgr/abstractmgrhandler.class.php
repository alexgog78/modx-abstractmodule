<?php

if (!class_exists('abstractHandler')) {
    require_once dirname(dirname(__FILE__)) . '/abstracthandler.class.php';
}

abstract class abstractMgrHandler extends abstractHandler
{
    /**
     * @param modManagerController $controller
     */
    public function loadAssets(modManagerController $controller)
    {
        $controller->addCss($this->config['abstractСssUrl'] . 'mgr/default.css');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/abstractmodule.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/panel.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/formpanel.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/grid.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/window.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/widgets/page.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.local.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/select.remote.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/combo/browser.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/utils/notice.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/renderer.list.js');
        $controller->addJavascript($this->config['abstractJsUrl'] . 'mgr/misc/function.list.js');

        $controller->addCss($this->config['cssUrl'] . 'mgr/default.css');
        $controller->addJavascript($this->config['jsUrl'] . 'mgr/' . $this->module->objectType . '.js');
        $configJs = $this->modx->toJSON($this->config ?? []);
        $controller->addHtml('<script type="text/javascript">' . get_class($this->module) . '.config = ' . $configJs . ';</script>');
    }

    /**
     * @param modManagerController $controller
     */
    public function addLexicon(modManagerController $controller)
    {
        $controller->addLexiconTopic($this->module->objectType . ':default');
    }
}
