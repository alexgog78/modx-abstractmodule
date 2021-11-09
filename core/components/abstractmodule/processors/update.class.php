<?php

require_once __DIR__ . '/helpers/setboolean.trait.php';
require_once __DIR__ . '/helpers/softvalidate.trait.php';

abstract class abstractModuleUpdateProcessor extends modObjectUpdateProcessor
{
    use abstractModuleProcessorHelperSetBoolean;
    use abstractModuleProcessorHelperSoftValidate;

    /** @var string */
    public $objectType;

    /** @var string */
    public $classKey;

    /** @var xPDOObject */
    public $object;

    /** @var abstractModule */
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
        $this->languageTopics[] = $this->objectType . ':status';
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
}
