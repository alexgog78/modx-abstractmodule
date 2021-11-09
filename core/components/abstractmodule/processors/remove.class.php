<?php

abstract class abstractModuleRemoveProcessor extends modObjectRemoveProcessor
{
    /** @var string */
    public $objectType;

    /** @var string */
    public $classKey;

    /** @var xPDOObject */
    public $object;

    /** @var abstractModule */
    protected $service;

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
}
