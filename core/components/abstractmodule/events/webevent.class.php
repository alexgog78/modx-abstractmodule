<?php

abstract class abstractModuleWebEvent extends abstractModuleEvent
{
    /**
     * @return bool
     */
    protected function checkPermissions()
    {
        if ($this->modx->context->key == 'mgr') {
            return false;
        }
        return parent::checkPermissions();
    }
}
