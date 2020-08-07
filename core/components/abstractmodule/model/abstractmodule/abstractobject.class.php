<?php

abstract class AbstractObject extends xPDOObject
{
    /** @var string|null */
    public static $createdOnField = 'created_on';

    /** @var string|null */
    public static $createdByField = 'created_by';

    /** @var string|null */
    public static $updatedOnField = 'updated_on';

    /** @var string|null */
    public static $updatedByField = 'updated_by';

    /** @var array */
    public static $searchableFields = [];

    /** @var array */
    public $booleanFields = [];

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
}
