<?php

abstract class AbstractSimpleObject extends xPDOSimpleObject
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

    /** @var array */
    public $jsonFields = [];

    /**
     * AbstractSimpleObject constructor.
     * @param xPDO $xpdo
     */
    public function __construct(xPDO &$xpdo)
    {
        parent::__construct($xpdo);
        $this->prepareFields();
    }

    /**
     * @param null $cacheFlag
     * @return bool
     */
    public function save($cacheFlag = null)
    {
        $this->setJsonFields();
        return parent::save($cacheFlag);
    }

    private function prepareFields()
    {
        foreach ($this->_fieldMeta as $fieldKey => $fieldData) {
            $this->setBooleanFieldsList($fieldKey, $fieldData);
            $this->setJsonFieldsList($fieldKey, $fieldData);
        }
    }

    /**
     * @param string $key
     * @param array $meta
     */
    private function setBooleanFieldsList(string $key, array $meta)
    {
        if ($meta['phptype'] == 'boolean') {
            $this->booleanFields[] = $key;
        }
    }

    /**
     * @param string $key
     * @param array $meta
     */
    private function setJsonFieldsList(string $key, array $meta)
    {
        if ($meta['phptype'] == 'json') {
            $this->jsonFields[] = $key;
        }
    }

    private function setJsonFields()
    {
        foreach ($this->jsonFields as $field) {
            $values = parent::get($field);
            if (!$values) {
                continue;
            }
            foreach ($values as $key => $value) {
                if ($value == '') {
                    unset($values[$key]);
                }
            }
            parent::set($field, $values);
        }
    }
}
