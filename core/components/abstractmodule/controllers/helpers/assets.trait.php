<?php

trait abstractModuleControllerHelperAssets
{
    /**
     * @param string $script
     */
    public function addCss($script)
    {
        $script .= '?' . $this->getAssetsVersion();
        parent::addCss($script);
    }

    /**
     * @param string $script
     */
    public function addJavascript($script)
    {
        $script .= '?' . $this->getAssetsVersion();
        parent::addJavascript($script);
    }

    /**
     * @param string $script
     */
    public function addLastJavascript($script)
    {
        $script .= '?' . $this->getAssetsVersion();
        parent::addLastJavascript($script);
    }

    /**
     * @return string
     */
    protected function getAssetsVersion()
    {
        return $this->service->getAssetsVersion();
    }
}
