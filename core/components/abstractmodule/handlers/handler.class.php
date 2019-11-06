<?php

namespace abstractModule\Handlers;

use \abstractModule;
use \modX;
use \xPDO;

abstract class abstractHandler
{
    /** @var abstractModule */
    protected $module;

    /** @var modX */
    protected $modx;

    /** @var array */
    protected $config;

    /**
     * abstractHandler constructor.
     * @param abstractModule $module
     * @param array $config
     */
    function __construct(& $module, array $config = [])
    {
        $this->module = &$module;
        $this->modx = &$module->modx;
        $this->config = $config;
    }
}
