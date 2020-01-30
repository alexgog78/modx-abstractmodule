<?php

abstract class abstractObject extends xPDOObject
{
    /** @var array */
    protected $booleanFields = [];

    /**
     * @return array
     */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }
}
