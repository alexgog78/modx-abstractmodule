<?php

abstract class AbstractObjectGetProcessor extends modObjectGetProcessor
{
    public function beforeOutput() {
        $this->prepareJsonCombo();
        return parent::beforeOutput();
    }

    private function prepareJsonCombo()
    {
        $jsonFields = $this->object->jsonFields;
        foreach ($jsonFields as $field) {
            $combo = [];
            foreach ($this->object->get($field) ?? [] as $value) {
                if ($value === '' || is_array($value)) {
                    continue;
                }
                $combo[] = [
                    'value' => $value,
                ];
            }
            if ($combo) {
                $this->object->set($field, $combo);
            }
        }
    }
}
