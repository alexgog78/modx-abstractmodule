<?php

trait abstractModuleControllerHelperAssets
{
    /** @var string */
    protected $assetsVersion;

    protected function getAssetsVersion()
    {
        if (!$this->service) {
            $this->assetsVersion = '';
            return;
        }
        if ($this->service::DEVELOPER_MODE) {
            $this->assetsVersion = time();
            return;
        }
        $this->assetsVersion = $this->service::PKG_VERSION . '-' . $this->service::PKG_RELEASE;
    }

    protected function loadAbstractCssJs()
    {
        $this->service->loadMgrAbstractCssJs($this);
    }

    protected function loadDefaultCssJs()
    {
        $this->service->loadMgrDefaultCssJs($this);
    }

    /**
     * @param string $script
     */
    public function addCss($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addCss($script);
    }

    /**
     * @param string $script
     */
    public function addJavascript($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addJavascript($script);
    }

    /**
     * @param string $script
     */
    public function addLastJavascript($script)
    {
        $script .= '?' . $this->assetsVersion;
        parent::addLastJavascript($script);
    }
}
