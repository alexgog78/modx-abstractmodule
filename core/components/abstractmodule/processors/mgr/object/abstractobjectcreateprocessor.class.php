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
        $booleanFields = $this->object->getBooleanFields();
        foreach ($booleanFields as $field) {
            $this->setCheckbox($field);
        }
    }

    private function setCreatedOn()
    {
        if (key_exists('created_on', $this->object->_fields)) {
            $this->setProperty('created_on', date('Y-m-d H:i:s'));
        }
    }

    private function setCreatedBy()
    {
        if (key_exists('created_by', $this->object->_fields)) {
            $this->setProperty('created_by', $this->modx->user->id);
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
