<?php

abstract class AbstractObjectUpdateProcessor extends modObjectUpdateProcessor
{
    /**
     * @return mixed
     */
    public function beforeSet()
    {
        $this->setBoolean();
        return parent::beforeSet();
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $this->setUpdatedOn();
        $this->setUpdatedBy();
        $this->validateElement();
        return parent::beforeSave();
    }

    private function setBoolean()
    {
        $booleanFields = $this->object->booleanFields;
        foreach ($booleanFields as $field) {
            $this->setCheckbox($field);
        }
    }

    private function setUpdatedOn()
    {
        if ($this->object::$updatedOnField) {
            $this->object->set($this->object::$updatedOnField, date('Y-m-d H:i:s'));
        }
    }

    private function setUpdatedBy()
    {
        if ($this->object::$updatedByField) {
            $this->object->set($this->object::$updatedByField, $this->modx->user->id);
        }
    }

    private function validateElement()
    {
        if (!$this->object->validate()) {
            /** @var modValidator $validator */
            $validator = $this->object->getValidator();
            if ($validator->hasMessages()) {
                foreach ($validator->getMessages() as $message) {
                    $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
                }
            }
        }
    }
}
