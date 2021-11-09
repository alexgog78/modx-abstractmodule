<?php

trait abstractModuleProcessorHelperComboQuery
{
    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function comboQuery(xPDOQuery $c)
    {
        return $c;
    }

    /**
     * @return bool
     */
    private function isComboQuery()
    {
        return $this->getProperty('combo') ? true : false;
    }
}
