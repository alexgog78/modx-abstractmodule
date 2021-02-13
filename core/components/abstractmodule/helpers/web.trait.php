<?php

trait abstractModuleHelperWeb
{
    /** @var string */
    protected $webAssetsVersion;

    /** @var array */
    protected $webConfig = [];

    public function loadWebDefaultCssJs()
    {
        $this->setWebConfig();
        $this->setWebAssetsVersion();
        $configJs = $this->modx->toJSON($this->webConfig);
        $this->modx->regClientScript('<script type="text/javascript">' . get_class($this) . ' = ' . $configJs . ';</script>', true);
        $this->getWebCssJs();
    }

    public function setWebConfig()
    {
        $this->webConfig = [
            'cssUrl' => $this->cssUrl . 'web/',
            'jsUrl' => $this->jsUrl . 'web/',
            'actionUrl' => $this->actionUrl,
            'actionKey' => $this::PKG_NAMESPACE . '_action',
        ];
    }

    public function getWebCssJs()
    {
        $defaultCss = $this->modx->getOption($this::PKG_NAMESPACE . '_frontend_css');
        if (!empty($defaultCss) && preg_match('/\.css/i', $defaultCss)) {
            $defaultCss = str_replace('[[+cssUrl]]', $this->webConfig['cssUrl'], $defaultCss);
            if (preg_match('/\.css$/i', $defaultCss)) {
                $defaultCss .= '?v=' . $this->webAssetsVersion;
            }
            $this->modx->regClientCSS($defaultCss);
        }
        $defaultJs = $this->modx->getOption($this::PKG_NAMESPACE . '_frontend_js');
        if (!empty($defaultJs) && preg_match('/\.js/i', $defaultJs)) {
            $defaultJs = str_replace('[[+jsUrl]]', $this->webConfig['jsUrl'], $defaultJs);
            if (preg_match('/\.js$/i', $defaultJs)) {
                $defaultJs .= '?v=' . $this->webAssetsVersion;
            }
            $this->modx->regClientScript($defaultJs);
        }
    }

    protected function setWebAssetsVersion()
    {
        if ($this::DEVELOPER_MODE) {
            $this->webAssetsVersion = time();
            return;
        }
        $this->webAssetsVersion = $this::PKG_VERSION . '-' . $this::PKG_RELEASE;
    }
}
