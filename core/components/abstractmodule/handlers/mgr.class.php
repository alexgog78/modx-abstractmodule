<?php

abstract class AbstractHandlerMgr extends AbstractHandler
{
    /**
     * @param modManagerController $controller
     */
    public function loadAssets(modManagerController $controller)
    {
        $controller->addCss($this->module->abstractСssUrl . 'mgr/default.css');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/abstractmodule.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/widgets/panel.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/widgets/formpanel.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/widgets/grid.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/widgets/window.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/widgets/page.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/combo/select.local.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/combo/select.remote.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/combo/browser.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/utils/notice.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/misc/renderer.list.js');
        $controller->addJavascript($this->module->abstractJsUrl . 'mgr/misc/function.list.js');

        $controller->addCss($this->module->cssUrl . 'mgr/default.css');
        $controller->addJavascript($this->module->jsUrl . 'mgr/' . $this->module->namespace . '.js');
        $configJs = $this->modx->toJSON($this->config ?? []);
        $controller->addHtml('<script type="text/javascript">' . get_class($this->module) . '.config = ' . $configJs . ';</script>');
    }

    /**
     * @param modManagerController $controller
     */
    public function addLexicon(modManagerController $controller)
    {
        $controller->addLexiconTopic($this->module->namespace . ':default');
    }
}
