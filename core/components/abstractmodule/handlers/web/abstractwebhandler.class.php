<?php

if (!class_exists('abstractHandler')) {
    require_once dirname(dirname(__FILE__)) . '/abstracthandler.class.php';
}

abstract class abstractWebHandler extends abstractHandler
{
    public function loadAssets()
    {
        $configJs = $this->modx->toJSON([
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl'],
        ]);

        $this->modx->regClientStartupScript('<script type="text/javascript">' . get_class($this->module) . 'Config = ' . $configJs . ';</script>', true);
    }
}
