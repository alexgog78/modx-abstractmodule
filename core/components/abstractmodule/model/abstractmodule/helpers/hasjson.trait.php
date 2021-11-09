<?php

trait abstractObjectHasJson
{
    /** @var bool */
    protected $json = true;

    /**
     * @return bool
     */
    protected function hasJson()
    {
        return $this->json;
    }

    /**
     * @return $this
     */
    protected function setJson()
    {
        foreach ($this->_fieldMeta as $field => $meta) {
            if ($meta['phptype'] != 'json') {
                continue;
            }
            $values = parent::get($field);
            if (!$values) {
                continue;
            }
            foreach ($values as $key => $value) {
                if ($value === '') {
                    unset($values[$key]);
                }
            }
            $this->setJsonField($field, $values);
        }
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @return $this
     */
    protected function setJsonField(string $field, array $values)
    {
        parent::set($field, $values);
        return $this;
    }
}
