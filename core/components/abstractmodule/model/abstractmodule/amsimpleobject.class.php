<?php

abstract class amSimpleObject extends xPDOSimpleObject
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
