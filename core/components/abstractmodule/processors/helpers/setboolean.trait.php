<?php

trait abstractModuleProcessorHelperSetBoolean
{
    protected function setBoolean()
    {
        foreach ($this->object->_fieldMeta as $fieldKey => $meta) {
            if ($meta['phptype'] != 'boolean') {
                continue;
            }
            $this->setCheckbox($fieldKey);
        }
    }
}
