<?php

abstract class amObject extends xPDOObject
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
