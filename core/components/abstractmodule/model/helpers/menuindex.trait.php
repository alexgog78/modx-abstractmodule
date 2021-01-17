<?php

/**
 * TODO
 * issue after deleting not last
 */
trait abstractModuleModelHelperMenuindex
{
    private function setMenuindex()
    {
        if ($this->_new) {
            $menuIndex = $this->xpdo->getCount(get_class($this), $this->getMenuindexConditions());
            $this->set($this->menuindexField, $menuIndex);
        }
    }

    private function removeMenuindex()
    {
    }

    /**
     * @return array
     */
    protected function getMenuindexConditions()
    {
        return [];
    }
}
