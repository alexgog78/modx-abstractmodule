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
        $booleanFields = $this->object->getBooleanFields();
        foreach ($booleanFields as $field) {
            $this->setCheckbox($field);
        }
    }

    private function setUpdatedOn()
    {
        if (key_exists('updated_on', $this->object->_fields)) {
            $this->object->set('updated_on', date('Y-m-d H:i:s'));
        }
    }

    private function setUpdatedBy()
    {
        if (key_exists('updated_by', $this->object->_fields)) {
            $this->object->set('updated_by', $this->modx->user->id);
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
