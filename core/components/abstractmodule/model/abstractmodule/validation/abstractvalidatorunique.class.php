<?php

abstract class abstractValidatorUnique extends xPDOValidationRule
{
    /** @var array */
    private $query = [];

    /** @var xPDOObject */
    private $object;

    /**  @var xPDO */
    private $xpdo;

    /**
     * @param mixed $value
     * @param array $options
     * @return bool
     */
    public function isValid($value, array $options = [])
    {
        parent::isValid($value, $options);

        $this->xpdo = &$this->validator->object->xpdo;
        $this->object = $this->validator->object;
        $this->query[$this->field] = $value;

        $this->pkQuery();

        $excludeFields = $options['excludeFields'];
        if (!empty($excludeFields)) {
            $excludeFields = explode(',', $excludeFields);
            $this->excludeQuery($excludeFields);
        }

        $count = $this->xpdo->getCount($this->object->_class, $this->query);

        $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, print_r($this->query, true));

        $result = ($count == 0);
        if ($result === false) {
            $this->validator->addMessage($this->field, $this->name, $this->message);
        }
        return $result;
    }

    private function pkQuery()
    {
        $pk = $this->object->getPK();
        if (is_array($pk)) {
            foreach ($pk as $pkItem) {
                $this->query[$pkItem . '!='] = $this->object->$pkItem;
            }
        } else {
            $this->query[$pk . '!='] = $this->object->$pk;
        }
    }

    private function excludeQuery($fields)
    {
        foreach ($fields as $field) {
            $this->query[$field] = $this->object->$field;
        }
    }
}
