<?php

abstract class abstractHandler
{
    /** @var abstractModule */
    protected $module;

    /** @var modX */
    protected $modx;

    /** @var array */
    public $config;

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
