<?php

abstract class AbstractObject extends xPDOObject
{
    /** @var array */
    protected $searchableFields = [];

    /** @var array */
    private $booleanFields = [];

    /**
     * AbstractObject constructor.
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

    /**  @return array */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }

    /**
     * @return array
     */
    public function getSearchableFields()
    {
        return $this->searchableFields;
    }
}
