<?php

abstract class AbstractObjectCreateProcessor extends modObjectCreateProcessor
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
        $this->setCreatedOn();
        $this->setCreatedBy();
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

    private function setCreatedOn()
    {
        if ($this->object::$createdOnField) {
            $this->object->set($this->object::$createdOnField, date('Y-m-d H:i:s'));
        }
    }

    private function setCreatedBy()
    {
        if ($this->object::$createdByField) {
            $this->object->set($this->object::$createdByField, $this->modx->user->id);
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
