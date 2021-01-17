<?php

//TODO
trait abstractModuleHelperWeb
{
    public function loadWebDefaultCssJs()
    {
        $configJs = $this->modx->toJSON([
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl'],
        ]);
        $this->modx->regClientStartupScript('<script type="text/javascript">' . get_class($this) . '.config = ' . $configJs . ';</script>', true);
    }

    public function getWebConfig()
    {

    }
}
