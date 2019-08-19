<?php

abstract class amObjectUpdateProcessor extends modObjectUpdateProcessor
{
    /**
     * @return mixed
     * TODO Combo-boolean
     */
    public function beforeSet()
    {
        //Combo-boolean
        $this->setBoolean();
        //$this->setCheckbox('is_active');
        /*$boolean = ['is_active', 'is_default'];
        foreach ($boolean as $tmp) {
            if ($this->getProperty($tmp) == $this->modx->lexicon('yes') || $this->getProperty($tmp) == 1) {
                $this->setProperty($tmp, true);
            } else {
                $this->setProperty($tmp, false);
            }
        }*/
        //var_dump($this->getProperty('xtype'));

        return parent::beforeSet();
    }


    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (!$this->validateRequiredFields()) {
            return false;
        }
        if (!$this->validateUniqueFields()) {
            return false;
        }

        return parent::beforeSave();
    }

    /**
     * @return void
     */
    private function setBoolean()
    {
        foreach ($this->object->getBooleanFields() as $field) {
            $this->setCheckbox($field);
        }
    }

    /**
     * @return bool
     */
    private function validateRequiredFields()
    {
        foreach ($this->object->getRequiredFields() as $tmp) {
            $property = $this->getProperty($tmp);
            if (is_array($property)) {
                $property = array_filter($property, 'strlen');
            }

            if (empty($property)) {
                if (is_array($property)) {
                    $tmp .= '[]';
                }
                $this->addFieldError($tmp, $this->modx->lexicon('field_required'));
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     * TODO refactor
     */
    private function validateUniqueFields()
    {
        foreach ($this->object->getUniqueFields() as $tmp) {
            $checkQuery = [
                $tmp => $this->getProperty($tmp)
            ];

            if ($this->object instanceof xPDOSimpleObject) {
                $checkQuery['id:!='] = $this->getProperty('id');
            }

            if (!empty($this->object->getUniqueFieldsConditions())) {
                foreach ($this->object->getUniqueFieldsConditions() as $key => $value) {
                    $checkQuery[$key] = $this->getProperty($value);
                }
            }

            if ($this->modx->getCount($this->classKey, $checkQuery)) {
                 $this->addFieldError($tmp, $this->modx->lexicon($this->objectType . '.err_ae'));
                return false;
            }
        }
        return true;
    }
}