<?php

trait abstractModuleControllerHelperService
{
    /** @var abstractModule */
    protected $service;

    protected function getService()
    {
        $this->service =  $this->modx->getService($this->namespace, $this->namespace, $this->namespace_path . '/model/');
    }
}
