<?php

abstract class AbstractHandler
{
    /** @var AbstractModule */
    protected $module;

    /** @var modX */
    protected $modx;

    public function __construct(AbstractModule $module)
    {
        $this->module = $module;
        $this->modx = $module->modx;
    }
}
