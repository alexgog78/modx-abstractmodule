<?php

abstract class AbstractHandler
{
    /** @var AbstractModule */
    protected $module;

    /** @var modX */
    protected $modx;

    /** @var array */
    public $config;

    /**
     * AbstractHandler constructor.
     * @param AbstractModule $module
     * @param array $config
     */
    public function __construct(& $module, array $config = [])
    {
        $this->module = &$module;
        $this->modx = &$module->modx;
        $this->config = $config;
    }
}
