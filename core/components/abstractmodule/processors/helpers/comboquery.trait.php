<?php

trait abstractModuleProcessorHelperComboQuery
{
    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    protected function comboQuery(xPDOQuery $c)
    {
        $c->where(['is_active' => 1]);
        return $c;
    }

    /**
     * @return bool
     */
    private function isComboQuery()
    {
        return ($this->getProperty('combo')) ? true : false;
    }
}
