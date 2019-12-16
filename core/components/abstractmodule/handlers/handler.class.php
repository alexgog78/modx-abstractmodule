<?php

abstract class amHandler
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
    public function __construct(& $module, array $config = [])
    {
        $this->module = &$module;
        $this->modx = &$module->modx;
        $this->config = $config;
    }
}
