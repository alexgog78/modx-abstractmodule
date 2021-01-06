<?php

trait abstractModuleControllerHelperService
{
    /** @var abstractModule */
    protected $service;

    /**
     * @return object|null
     */
    protected function getService()
    {
        return $this->modx->getService($this->namespace, $this->namespace, $this->namespace_path . '/model/');
    }
}
