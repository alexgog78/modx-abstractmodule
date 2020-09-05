<?php

trait AbstractMgrHelper
{
    /**
     * @param modManagerController $controller
     */
    public function loadDefaultMgrAssets(modManagerController $controller)
    {
        $controller->addCss($this->service->abstractСssUrl . 'mgr/default.css');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/abstractmodule.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/panel.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/formpanel.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/grid.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/localgrid.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/window.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/page.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/combo/select.local.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/combo/multiselect.local.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/combo/select.remote.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/combo/multiselect.remote.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/combo/browser.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/misc/renderer.list.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/misc/function.list.js');
        $controller->addJavascript($this->service->abstractJsUrl . 'mgr/misc/component.list.js');

        $controller->addCss($this->service->cssUrl . 'mgr/default.css');
        $controller->addJavascript($this->service->jsUrl . 'mgr/' . $this->service->namespace . '.js');
        $configJs = $this->modx->toJSON($this->service->config ?? []);
        $controller->addHtml('<script type="text/javascript">' . get_class($this->service) . '.config = ' . $configJs . ';</script>');
    }

    /**
     * @param modManagerController $controller
     */
    public function addMgrLexicon(modManagerController $controller)
    {
        $controller->addLexiconTopic($this->service->namespace . ':default');
    }
}
