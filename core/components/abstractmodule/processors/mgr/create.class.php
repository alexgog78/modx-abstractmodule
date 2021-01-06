<?php

require_once dirname(__DIR__) . '/helpers/setboolean.trait.php';

abstract class abstractModuleCreateProcessor extends modObjectCreateProcessor
{
    use abstractModuleProcessorHelperSetBoolean;

    /** @var string */
    public $objectType = 'samplemodule';

    /** @var array */
    public $languageTopics = [
        'samplemodule:status',
    ];

    /** @var object */
    protected $service;

    /** @var bool */
    protected $softValidate = true;

    /**
     * @param modX $modx
     * @param array $properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);
        $this->service = $this->modx->{$this->objectType};
    }

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
        if ($this->softValidate) {
            $this->softValidate();
        }
        return parent::beforeSave();
    }

    private function softValidate()
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
