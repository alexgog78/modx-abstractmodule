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
        $createdOnField = $this->getCreatedOnField();
        $this->set($createdOnField, date('Y-m-d H:i:s'));
        $createdByField = $this->getCreatedByField();
        $this->set($createdByField, $this->xpdo->user->id);
    }

    private function setUpdatedFields()
    {
        $updatedOnField = $this->getUpdatedOnField();
        $this->set($updatedOnField, date('Y-m-d H:i:s'));
        $updatedByField = $this->getUpdatedByField();
        $this->set($updatedByField, $this->xpdo->user->id);
    }

    /**
     * @return string
     */
    private function getCreatedOnField()
    {
        return $this->createdOnField ?? 'created_on';
    }

    /**
     * @return string
     */
    private function getCreatedByField()
    {
        return $this->createdByField ?? 'created_by';
    }

    /**
     * @return string
     */
    private function getUpdatedOnField()
    {
        return $this->updatedOnField ?? 'updated_on';
    }

    /**
     * @return string
     */
    private function getUpdatedByField()
    {
        return $this->updatedByField ?? 'updated_by';
    }
}
