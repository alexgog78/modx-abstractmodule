<?php

trait abstractModuleModelHelperTimestamps
{
    private function setTimestamps()
    {
        if ($this->_new) {
            $this->setCreatedFields();
        } else {
            $this->setUpdatedFields();
        }
    }

    private function setCreatedFields()
    {
        $this->set($this->createdOnField, date('Y-m-d H:i:s'));
        $this->set($this->createdByField, $this->xpdo->user->id);
    }

    private function setUpdatedFields()
    {
        $this->set($this->updatedOnField, date('Y-m-d H:i:s'));
        $this->set($this->updatedByField, $this->xpdo->user->id);
    }
}
