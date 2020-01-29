<?php

if (!class_exists('amHandler')) {
    require_once dirname(__FILE__) . '/handler.class.php';
}

abstract class amWebHandler extends amHandler
{
    public function __construct(& $module, array $config = [])
    {
        parent::__construct($module, $config);
        /*if ($this->modx->controller) {
            $this->addBackendAssets($this->modx->controller);
        }*/
        //$this->addFrontendAssets();
    }

    /**
     * @return bool
     */
    public function loadAssets()
    {
        $configJs = $this->modx->toJSON(array(
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl']
        ));

        //$this->modx->regClientCSS('assets/css/my-custom.css');

        //TODO addJavascript
        $this->modx->regClientStartupScript(
            '<script type="text/javascript">' . get_class($this->module) . 'Config = ' . $configJs . ';</script>',
            true
        );
        return true;
    }
}
