<?php

require_once dirname(__DIR__) . '/helpers/setboolean.trait.php';
require_once dirname(__DIR__) . '/helpers/softvalidate.trait.php';

abstract class abstractModuleCreateProcessor extends modObjectCreateProcessor
{
    use abstractModuleProcessorHelperSetBoolean;
    use abstractModuleProcessorHelperSoftValidate;

    /** @var string */
    public $objectType;

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
