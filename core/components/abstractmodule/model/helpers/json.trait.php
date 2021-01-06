<?php

trait abstractModuleModelHelperJson
{
    private function setJsonFields()
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
                if ($value == '') {
                    unset($values[$key]);
                }
            }
            parent::set($field, $values);
        }
    }
}
