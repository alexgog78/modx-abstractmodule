<?php

abstract class amObjectUpdateProcessor extends modObjectUpdateProcessor
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
        $this->validateElement();
        return parent::beforeSave();
    }

    private function setBoolean()
    {
        foreach ($this->object->getBooleanFields() as $field) {
            $this->setCheckbox($field);
        }
    }

    private function validateElement() {
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
