<?php

abstract class abstractModuleDefaultProcessor extends modProcessor
{
    /** @var string */
    public $objectType;

    /** @var array */
    public $languageTopics = [];

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

    /**
     * @return array
     */
    public function getLanguageTopics() {
        return $this->languageTopics;
    }
}
