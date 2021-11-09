<?php

trait abstractModuleAssetsHelper
{
    /** @var string */
    protected $assetsVersion = '';

    /**
     * @return string
     */
    public function getAssetsVersion()
    {
        $this->assetsVersion = $this::PKG_VERSION . '-' . $this::PKG_RELEASE;
        if ($this::DEVELOPER_MODE) {
            $this->assetsVersion = time();
        }
        return $this->assetsVersion;
    }

    public function loadWebAssets()
    {
        $configJs = $this->getWebDefaultAssetsConfig();
        $defaultCss = $this->getOption('frontend_css');
        $defaultJs = $this->getOption('frontend_js');
        $this->loadWebStartupScript($configJs)
            ->loadWebCSS($defaultCss)
            ->loadWebScript($defaultJs);
    }

    /**
     * @param modManagerController $controller
     */
    public function loadMgrAssets(modManagerController $controller)
    {
        $this->loadMgrAbstractAssets($controller)
            ->loadMgrDefaultAssets($controller);
    }

    /**
     * @return array
     */
    protected function getWebDefaultAssetsConfig()
    {
        return [
            'cssUrl' => $this->cssUrl,
            'jsUrl' => $this->jsUrl,
            'actionUrl' => $this->actionUrl,
            'actionKey' => $this->actionKey,
        ];
    }

    /**
     * @return array
     */
    protected function getMgrDefaultAssetsConfig()
    {
        return $this->getConfig();
    }

    /**
     * @param array $configJs
     * @return $this
     */
    protected function loadWebStartupScript($configJs = [])
    {
        $this->modx->regClientStartupScript('<script type="text/javascript">' . $this::PKG_NAME . ' = ' . $this->modx->toJSON($configJs) . ';</script>', true);
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    protected function loadWebCSS($file = '')
    {
        if (empty($file) || !preg_match('/\.css/i', $file)) {
            return $this;
        }
        $file = str_replace('[[+cssUrl]]', $this->cssUrl, $file);
        if (preg_match('/\.css$/i', $file)) {
            $file .= '?v=' . $this->getAssetsVersion();
        }
        $this->modx->regClientCSS($file);
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    protected function loadWebScript($file = '')
    {
        if (empty($file) || !preg_match('/\.js/i', $file)) {
            return $this;
        }
        $file = str_replace('[[+jsUrl]]', $this->jsUrl, $file);
        if (preg_match('/\.js$/i', $file)) {
            $file .= '?v=' . $this->getAssetsVersion();
        }
        $this->modx->regClientScript($file);
        return $this;
    }

    /**
     * @param modManagerController $controller
     * @return $this
     */
    protected function loadMgrAbstractAssets(modManagerController $controller)
    {
        $controller->addCss($this->baseCssUrl . 'mgr/styles.css');
        $controller->addJavascript($this->baseJsUrl . 'mgr//abstractmodule.js');

        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/panel.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/formpanel.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/grid.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/localgrid.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/window.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//widgets/page.js');

        $controller->addJavascript($this->baseJsUrl . 'mgr//combo/select.local.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//combo/multiselect.local.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//combo/select.remote.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//combo/multiselect.remote.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//combo/browser.js');

        $controller->addJavascript($this->baseJsUrl . 'mgr//misc/function.list.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//misc/component.list.js');
        $controller->addJavascript($this->baseJsUrl . 'mgr//misc/renderer.list.js');
        return $this;
    }

    /**
     * @param modManagerController $controller
     * @return $this
     */
    protected function loadMgrDefaultAssets(modManagerController $controller)
    {
        $controller->addCss($this->cssUrl . 'mgr/default.css');
        $controller->addJavascript($this->jsUrl . 'mgr/' . $this::PKG_NAMESPACE . '.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/function.list.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/component.list.js');
        $controller->addJavascript($this->jsUrl . 'mgr/misc/renderer.list.js');

        $config = $this->getMgrDefaultAssetsConfig();
        $controller->addHtml('<script type="text/javascript">' . $this::PKG_NAME . '.config = ' . $this->modx->toJSON($config) . ';</script>');
        return $this;
    }
}
