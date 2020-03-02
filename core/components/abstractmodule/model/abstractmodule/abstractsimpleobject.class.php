<?php

abstract class AbstractSimpleObject extends xPDOSimpleObject
{
    /** @var array */
    private $booleanFields = [];

    /**
     * AbstractSimpleObject constructor.
     * @param xPDO $xpdo
     */
    public function __construct(xPDO &$xpdo)
    {
        parent::__construct($xpdo);
        $this->setBooleanFields();
    }

    public function setBooleanFields()
    {
        foreach ($this->_fieldMeta as $fieldKey => $fieldData) {
            if ($fieldData['phptype'] == 'boolean') {
                $this->booleanFields[] = $fieldKey;
            }
        }
    }

    /**
     * @return array
     */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }

    public function myCallable()
    {

    }
}
