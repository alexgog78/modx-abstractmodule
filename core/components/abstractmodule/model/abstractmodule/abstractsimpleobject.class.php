<?php

abstract class abstractSimpleObject extends xPDOSimpleObject
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
