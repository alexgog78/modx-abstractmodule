<?php

trait abstractModuleControllerHelperAssets
{
    protected function loadCoreCssJs()
    {
        $this->addCss($this->service->abstractСssUrl . 'mgr/styles.css');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/abstractmodule.js');

        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/panel.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/formpanel.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/grid.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/localgrid.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/window.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/widgets/page.js');

        $this->addJavascript($this->service->abstractJsUrl . 'mgr/combo/select.local.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/combo/multiselect.local.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/combo/select.remote.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/combo/multiselect.remote.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/combo/browser.js');

        $this->addJavascript($this->service->abstractJsUrl . 'mgr/misc/function.list.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/misc/component.list.js');
        $this->addJavascript($this->service->abstractJsUrl . 'mgr/misc/renderer.list.js');
    }

    protected function loadDefaultCssJs()
    {
        $this->addCss($this->service->cssUrl . 'mgr/default.css');
        $this->addJavascript($this->service->jsUrl . 'mgr/' . $this->service::PKG_NAMESPACE . '.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/misc/function.list.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/misc/component.list.js');
        $this->addJavascript($this->service->jsUrl . 'mgr/misc/renderer.list.js');
        $configJs = $this->modx->toJSON($this->service->config);
        $this->addHtml('<script type="text/javascript">' . get_class($this->service) . '.config = ' . $configJs . ';</script>');
    }
}
