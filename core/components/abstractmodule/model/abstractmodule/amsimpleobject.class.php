<?php

abstract class amSimpleObject extends xPDOSimpleObject
{
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