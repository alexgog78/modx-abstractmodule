<?php

abstract class amObject extends xPDOObject
{
    /**
     * @return array
     */
    public function getBooleanFields()
    {
        return $this::BOOLEAN_FIELDS ?? [];
    }

    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this::REQUIRED_FIELDS ?? [];
    }

    /**
     * @return array
     */
    public function getUniqueFields()
    {
        return $this::UNIQUE_FIELDS ?? [];
    }

    /**
     * @return array
     */
    public function getUniqueFieldsConditions()
    {
        return $this::UNIQUE_FIELDS_CHECK_BY_CONDITIONS ?? [];
    }
}
